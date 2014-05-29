<?php
namespace DMS\Core;

/**
 * Clase de principal/bootstrap del core
 * 
 * @package DMS-TORNADO-CORE
 * @author Daniel Martín Spiridione <info@daniel-spiridione.com.ar>
 * @link https://github.com/danielspk/DMS-PHP-CORE
 * @license https://github.com/danielspk/DMS-PHP-CORE/blob/master/LICENSE MIT
 * @version 0.8.0
 */
final class Tornado
{

	/**
	 * Instancia única de la clase (patrón singleton)
	 * @var DMS\Core\Tornado 
	 */
	private static $_instance = null;
	
	/**
	 * Clase de manejo de enrutamientos
	 * @var DMS\Core\Route 
	 */
	private $_route = null;
	
	/**
	 * Clase de manejo de autocarga de librerías
	 * @var DMS\Core\Autoload 
	 */
	private $_autoload = null;
	
	/**
	 * Clase de manejo de errors
	 * @var DMS\Core\Error 
	 */
	private $_error = null;
	
	/**
	 * Clase de manejo de configuración
	 * @var DMS\Core\Config 
	 */
	private $_config = array();
	
	/**
	 * Clase de manejo de ganchos y eventos
	 * @var DMS\Core\Hook
	 */
	private $_hook = array();
	
	/**
	 * Módulo a ejecutar
	 * @var string
	 */
	private $_module = null;
	
	/**
	 * Controlador a ejecutar
	 * @var string
	 */
	private $_controller = null;
	
	/**
	 * Método a ejecutar
	 * @var string
	 */
	private $_method = null;
	
	/**
	 * Parámetros del método a ejecutar
	 * @var array
	 */
	private $_params = array();
	
	/**
	 * Método constructor
	 */
	private function __construct() {
	
		require __DIR__ . '/autoload.php';
		require __DIR__ . '/error.php';
		require __DIR__ . '/config.php';
		require __DIR__ . '/hook.php';
		require __DIR__ . '/route.php';
		require __DIR__ . '/controller.php';

		$this->_autoload = new Autoload();
		$this->_error = new Error();
		$this->_config = new Config();
		$this->_hook = new Hook();
		$this->_route = new Route();
		
	}
	
	/**
	 * Método que instancia la clase o devuelve la misma (patrón singleton)
	 * @return DMS\Core\Tornado
	 */
	public static function getInstance()
	{
		if (self::$_instance === null) {
			self::$_instance = new self;
		}
		return self::$_instance;
	}
	
	/**
	 * Método que arranca la aplicación
	 * @return void
	 */
	public function run()
	{

		// se establece la codificación de funciones, entradas y salidas
		mb_internal_encoding('UTF-8');
		mb_http_output('UTF-8');
		mb_http_input('UTF-8');
		mb_language('uni');
		mb_regex_encoding('UTF-8');
		
		// se ejecuta el hook de inicio
		$this->_hook->call('init');
		
		// se carga el modulo HMVC correspondiente a la URL
		self::_parseURL();

		// se ejecuta el hook de finalización
		$this->_hook->call('end');
		
	}
	
	/**
	 * Método que registra enrutamientos a módulos
	 * @param string $pMethod Método de petición
	 * @param string $pRoute Patrón de la ruta
	 * @param mixed $pCallback Módulo o función a invocar
	 */
	public function route($pMethod, $pRoute, $pCallback)
	{
		$this->_route->register($pMethod, $pRoute, $pCallback);
	}
	
	/**
	 * Método que asigna o recupera valores de configuración
	 * @param string $pName Nombre del valor de configuración
	 * @param mixed $pValue Valor de configuración (puede ser un array)
	 * @return mixed
	 */
	public function config($pName, $pValue = null)
	{
		if (func_num_args() === 1){
			return $this->_config[$pName];
		}
		
		$this->_config[$pName] = $pValue;
	}
	
	/**
	 * Método que devuelve la instancia de la clase de autoload
	 * @return DMS\Core\Autoload
	 */
	public function autoload()
	{
		return $this->_autoload;
	}
	
	/**
	 * Método que asigna ganchos o devuelve la instancia de la clase de ganchos
	 * @param string $pName Nombre del gancho
	 * @param mixed $pCallback Callback a ejecutar
	 * @return mixed
	 */
	public function hook($pName = null, $pCallback = null)
	{
		if (func_num_args() === 0){
			return $this->_hook;
		}
		
		$this->_hook->register($pName, $pCallback);
	}
	
	/**
	 * Método que devuelve la instancia de la clase de manejo de errores
	 * @return DMS\Core\Error
	 */
	public function error(){
		return $this->_error;
	}
	
	/**
	 * Método que parsea la url a ejecutar
	 * @return void
	 */
	private function _parseURL()
	{
		
		// se determina si la URL esta enrutada hacia un módulo
		$querystring = (empty($_SERVER['QUERY_STRING'])) ? '/' : $_SERVER['QUERY_STRING'];
		
		// filtros de enrutadores y expresión resultante
		$tokens = array(
			':string' => '([a-zA-Z]+)',
			':number' => '([0-9]+)',
			':alpha'  => '([a-zA-Z0-9-_]+)'
		);
		
		// bandera que indica si la URL fue enrutada
		$routingHandler = false;
		
		// método de petición
		$method = $_SERVER["REQUEST_METHOD"];
		
		// sobrescritura del método de petición si el navegador no soporte PUT y DELETE
		if ($method == 'POST' && isset($_POST['REST_METHOD']) && $_POST['REST_METHOD'] == 'PUT') {
			$method = 'PUT';
		} else if ($method == 'POST' && isset($_POST['REST_METHOD']) && $_POST['REST_METHOD'] == 'DELETE') {
			$method = 'DELETE';
		}
		
		// se recorren las rutas registradas
		foreach ($this->_route->getRoutes() as $route /* as $pattern => $handler_name*/) {
			
			$pattern = strtr($route['route'], $tokens);
			
			if ($route['method'] == $method && preg_match('#^/?' . $pattern . '/?$#', $querystring, $matches)) {
				
				if (count($matches) > 1)
					$this->_params = array_slice($matches, 1);
				
				// se determina si hay una función anonima en vez de un módulo
				if (is_callable($route['callback'])) {
					
					call_user_func($route['callback'], $this->_params);
					return;
					
				} else {
					
					$handler = explode('@', $route['callback']);
			
					$this->_module = $handler[0];
					$this->_controller = $handler[1];
					$this->_method = $handler[2];

					$routingHandler = true;
				
				}
			
				break;
				
			}
			
		}
		
		// si la URL fue enrutada se invoca al callback del módulo parseado
		if ($routingHandler === true) {
			$this->_callModule();
			return;
		}
		
		// si la URL no fue enrutada se parsea directamente la URL en busca 
		// de un acceso directo hacia el módulo, 
		if ($_SERVER['QUERY_STRING']) {
			
			// se elimina la barra final de la url si existiese
			// se sanea la url
			// se separan las secciones de la url en un array
			$url = explode
			(
				'/',
				filter_var(
					rtrim(
						$_SERVER['QUERY_STRING'],
						'/'
					), 
					FILTER_SANITIZE_URL
				)
			);
			
			// dependiendo la cantidad de secciones de la url se conforma el
			// modulo, controlador, acción y parámetros
			$count = count($url);
			
			if($count == 1) {
				$url[1] = $url[0];
			}
		
			$this->_module = $url[0];
			$this->_controller = $url[1];

			if ($count > 2) {
				$this->_method = $url[2];
			} else {
				$this->_method = 'index';
			}
			
			if ($count > 3) {
				$this->_params = array_slice($url, 3);
			}
		
		} else {
			
			$this->_hook->call('404');
			return;

		}
		
		$this->_callModule();
		
	}
	
	/**
	 * Método que ejecuta el módulo\controlador\acción\parámetros parseado
	 * @return void
	 */
	private function _callModule()
	{
		
		// se valida que el parseo de la URL haya dado un módulo por resultado
		if (!$this->_module || !$this->_controller ) {
			$this->_hook->call('404');
			return;
		}
		
		// se valida si la ruta de la clase solicitada existe
		$path = 'app/modules/' . $this->_module . '/controller/' . $this->_controller . '.php';
		
		if (! file_exists($path)) {
			$this->_hook->call('404');
			return;
		} else {
			require $path;
		}
		
		// se agrega el namespace al controlador
		$this->_controller = 'App\Modules\\' . $this->_controller;
		
		// se valida si el método solicitado existe
		if (! method_exists($this->_controller, $this->_method)) {
			$this->_hook->call('404');
			return;
		}
		
		// se instancia el controlador;
		$controller = new $this->_controller();
		
		// se ejecuta la acción junto a sus parámetros si existiesen
		call_user_func_array( array($controller, $this->_method), $this->_params);

	}
	
}