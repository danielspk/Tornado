<?php
namespace DMS\Tornado;

/**
 * Clase de principal/bootstrap del core
 *
 * @package TORNADO-CORE
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link http://tornado-php.com
 * @license http://tornado-php.com/licencia/ MIT License
 * @version 1.0.0
 */
final class Tornado
{

    /**
     * Instancia única de la clase (patrón singleton)
     * @var \DMS\Tornado\Tornado
     */
    private static $_instance = null;

    /**
     * Clase de manejo de enrutamientos
     * @var \DMS\Tornado\Route
     */
    private $_route = null;

    /**
     * Clase de manejo de autocarga de librerías
     * @var \DMS\Tornado\Autoload
     */
    private $_autoload = null;

    /**
     * Clase de manejo de errors
     * @var \DMS\Tornado\Error
     */
    private $_error = null;

    /**
     * Clase de manejo de configuración
     * @var \DMS\Tornado\Config
     */
    private $_config = null;

    /**
     * Clase de manejo de ganchos y eventos
     * @var \DMS\Tornado\Hook
     */
    private $_hook = null;

    /**
     * Clase de manejo de anotaciones
     * @var \DMS\Tornado\Annotation
     */
    private $_annotation = null;

    /**
     * Servicios inyectados
     * @var array
     */
    private $_services = array();

    /**
     * Método constructor
     */
    private function __construct()
    {
        require __DIR__ . '/autoload.php';
        require __DIR__ . '/error.php';
        require __DIR__ . '/config.php';
        require __DIR__ . '/hook.php';
        require __DIR__ . '/route.php';
        require __DIR__ . '/controller.php';
        require __DIR__ . '/annotation.php';

        $this->_autoload = new Autoload();
        $this->_error = new Error();
        $this->_config = new Config();
        $this->_hook = new Hook();
        $this->_route = new Route();
        $this->_annotation = new Annotation();
    }

    /**
     * Método que impide la clonación de la clase singleton
     * @throws \BadMethodCallException
     */
    public function __clone()
    {
        throw new \BadMethodCallException('Tornado cannot be cloned.');
    }

    /**
     * Método que instancia la clase o devuelve la misma (patrón singleton)
     * @return \DMS\Tornado\Tornado
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

        // se establecen comportamientos según el ambiente de la aplicación
        if ($this->_config['tornado_environment_development'] == true) {

            ini_set('display_errors', '1');
            error_reporting(E_ALL);

            $this->_annotation->findRoutes();

        } else {

            ini_set('display_errors', '0');
            error_reporting(0);

        }

        // se registran las rutas serializadas
        $this->_route->unserialize();

        // flujo de ejecución:
        // - se parsea la url en busca del route a ejecutar
        // - se ejecutan los hooks de inicio
        // - si alguno no devuelve false se ejecuta la ruta
        // - si la ruta no fue ejecuta se ejecuta el hook de error
        // - se ejecutan los hooks de finalización

        $this->_route->parseUrl();

        $flowReturn = $this->_hook->call('init');

        if ($flowReturn !== false) {

            $flowReturn = $this->_route->execute();

            if ($flowReturn === false)
                $this->_hook->call('404');
        }

        $this->_hook->call('end');
    }

    /**
     * Método que registra enrutamientos a módulos
     * @param string $pMethodRoute Método de petición y Patrón de ruta
     * @param mixed  $pCallback    Módulo o función a invocar
     */
    public function route($pMethodRoute, $pCallback)
    {
        $this->_route->register($pMethodRoute, $pCallback);
    }

    /**
     * Método que asigna o recupera valores de configuración
     * @param  string $pName  Nombre del valor de configuración
     * @param  mixed  $pValue Valor de configuración (puede ser un array)
     * @return mixed
     */
    public function config($pName, $pValue = null)
    {
        if (func_num_args() === 1) {
            return $this->_config[$pName];
        }

        $this->_config[$pName] = $pValue;
    }

    /**
     * Método que habilita o no el manejador de autoload, o registra un namespace/directorio
     * @param  string $pPrefix  Prefijo del namespace a registrar
     * @param  array  $pBaseDir Rutas de directorios que contienen las clases del namespace
     * @return mixed
     */
    public function autoload($pPrefix = null, $pBaseDir = null)
    {
        if (func_num_args() === 1) {

            $this->_autoload->register($pPrefix);

            return;
        }

        $this->_autoload->addNamespace($pPrefix, $pBaseDir);
    }

    /**
     * Método que registra ganchos o invoca a uno
     * @param  string $pName     Nombre del gancho
     * @param  mixed  $pCallback Callback a ejecutar
     * @param  mixed  $pPosition Orden del gancho
     * @return mixed
     */
    public function hook($pName = null, $pCallback = null, $pPosition = null)
    {
        if (func_num_args() === 1) {

            $this->_hook->call($pName);

            return;
        }

        $this->_hook->register($pName, $pCallback, $pPosition);
    }

    /**
     * Método que habilita o no la gestión de errores/excepciones, o devuelve la última excepción lanzada
     * @param  mixed $pParam Booleano (para habilitar/deshabilitar) o null para devolver excepción
     * @return mixed
     */
    public function error($pParam = null)
    {
        if (is_bool($pParam)) {

            $this->_error->setHandler($pParam);

            return;
        }

        return $this->_error->getCurrentException();
    }

    /**
     * Método que carga un template/vista
     * @param string $pTemplate Archivo de template/vista
     * @param array  $pParams   Variables
     */
    public function render($pTemplate, $pParams = null)
    {
        if (is_array($pParams)) {
            extract($pParams);
            unset($pParams);
        }

        require_once $pTemplate;
    }

    /**
     * Método que recupera un parámetro del enrutamiento actual
     * @param  string $pName Nombre
     * @return string
     */
    public function param($pName)
    {
        return $this->_route->getParam($pName);
    }

    public function getRouteModule()
    {
        return $this->_route->getRouteMatch();
    }

    /**
     * Método que registra un servicio/clase externa
     * @param string   $pService  Nombre del servicio a registrar
     * @param callable $pCallback Función a ejecutar al momento de invocarse
     */
    public function register($pService, $pCallback)
    {
        $this->_services[$pService] = $pCallback;
    }

    /**
     * Método que delega la petición a otro módulo
     * @param string $pModule Nombre de la ruta a delegar
     * @param array  $pParams Parámetros para la ruta
     */
    public function forward($pModule, $pParams = null)
    {
        $module = explode('|', $pModule);
        
        if (count($module) !== 3) {
            throw new \BadMethodCallException('Invalid module name.');
        }
        
        $this->_route->callModule($module[0], $module[1], $module[2], $pParams);
    }
    
    /**
     * Método mágico __call. Se asume que es invocado al solicitar un servicio inyectado
     * @param  string $pService Nombre del servicio
     * @param  array  $pArgs    Parámetros
     * @return object
     */
    public function __call($pService, $pArgs)
    {
        if (!isset($this->_services[$pService])) {
            throw new \BadMethodCallException('The service ' . $pService . ' is not registered.');
        }

        return call_user_func_array($this->_services[$pService], $pArgs);
    }

    /**
     * Método mágico __get. Se asume que es invocado al solicitar un servicio inyectado
     * @param  string $pService Nombre del servicio
     * @return object
     */
    public function __get($pService)
    {
        if (!isset($this->_services[$pService])) {
            throw new \InvalidArgumentException('The service ' . $pService . ' is not registered.');
        }

        return $this->_services[$pService]();
    }

}
