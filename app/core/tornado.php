<?php
namespace DMS\Tornado;

/**
 * Clase de principal/bootstrap del core
 *
 * @package TORNADO-CORE
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link https://github.com/danielspk/TORNADO
 * @license https://github.com/danielspk/TORNADO/blob/master/LICENSE MIT License
 * @version 0.9.5
 */
final class Tornado
{

    /**
     * Instancia única de la clase (patrón singleton)
     * @var DMS\Tornado\Tornado
     */
    private static $_instance = null;

    /**
     * Clase de manejo de enrutamientos
     * @var DMS\Tornado\Route
     */
    private $_route = null;

    /**
     * Clase de manejo de autocarga de librerías
     * @var DMS\Tornado\Autoload
     */
    private $_autoload = null;

    /**
     * Clase de manejo de errors
     * @var DMS\Tornado\Error
     */
    private $_error = null;

    /**
     * Clase de manejo de configuración
     * @var DMS\Tornado\Config
     */
    private $_config = null;

    /**
     * Clase de manejo de ganchos y eventos
     * @var DMS\Tornado\Hook
     */
    private $_hook = null;

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

        $this->_autoload = new Autoload();
        $this->_error = new Error();
        $this->_config = new Config();
        $this->_hook = new Hook();
        $this->_route = new Route();

    }

    /**
     * Método que impide la clonación de la clase singleton
     * @throws \BadMethodCallException
     */
    public function __clone()
    {
        throw new \BadMethodCallException('Tornado no puede ser clonado.');
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

        // se carga el modulo correspondiente a la URL
        self::_parseURL();

        // se ejecuta el hook de finalización
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
     * @return mixed
     */
    public function hook($pName = null, $pCallback = null)
    {
        if (func_num_args() === 1) {

            $this->_hook->call($pName);

            return;
        }

        $this->_hook->register($pName, $pCallback);
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
     * Método que parsea la url a ejecutar
     * @return void
     */
    private function _parseURL()
    {

        // se ajustan las barras de la query string
        $querystring = (empty($_SERVER['QUERY_STRING'])) ? '/' : $_SERVER['QUERY_STRING'];
        $querystring .= (substr($querystring, -1) != '/') ? '/' : '';

        // filtros de enrutadores y expresión resultante
        $tokens = array(
            ':string'    => '([a-zA-Z]+)',
            ':number'    => '([0-9]+)',
            ':alpha'     => '([a-zA-Z0-9-_]+)',
            '[/:string]' => '/?([a-zA-Z]+)?',
            '[/:number]' => '/?([0-9]+)?',
            '[/:alpha]'  => '/?([a-zA-Z0-9-_]+)?'
        );

        // método de petición
        $method = $_SERVER["REQUEST_METHOD"];

        // sobrescritura del método de petición si el navegador no soporta PUT y DELETE
        if ($method == 'POST' && isset($_POST['REST_METHOD']) && $_POST['REST_METHOD'] == 'PUT') {
            $method = 'PUT';
        } elseif ($method == 'POST' && isset($_POST['REST_METHOD']) && $_POST['REST_METHOD'] == 'DELETE') {
            $method = 'DELETE';
        }

        // se recorren las rutas registradas
        foreach ($this->_route->getRoutes() as $route) {

            $routeMatch = strtr($route['route'], $tokens);

            if (
                ($route['method'] == 'ALL' || strstr($route['method'], $method) !== false) &&
                preg_match('#^/?' . $routeMatch . '/?$#', $querystring, $matches)
            ) {

                // se determina si hay una función anonima en vez de un módulo
                if (is_callable($route['callback'])) {

                    if (count($matches) > 1) {
                        $params = array_slice($matches, 1);
                    } else {
                        $params = array();
                    }

                    call_user_func_array($route['callback'], $params);

                } else {

                    $handler = explode('\\', $route['callback']);

                    $this->callModule($handler[0], $handler[1], $handler[2]);

                }

                return;

            }

        }

        // si la URL no fue enrutada se parsea directamente la URL en busca
        // de un acceso directo hacia el módulo,
        if ($_SERVER['QUERY_STRING']) {

            // se elimina la barra final e inicial de la url si existiesen
            // se sanea la url
            // se separan las secciones de la url en un array
            $url = explode
            (
                '/',
                filter_var(
                    trim(
                        $_SERVER['QUERY_STRING'],
                        '/'
                    ),
                    FILTER_SANITIZE_URL
                )
            );

            // dependiendo la cantidad de secciones de la url se conforma el
            // modulo, controlador, acción y parámetros
            $count = count($url);

            if ($count == 1) {
                $url[1] = $url[0];
            }

            $module = $url[0];
            $controller = $url[1];

            if ($count > 2) {
                $method = $url[2];
            } else {
                $method = 'index';
            }

            if ($count > 3) {
                $params = array_slice($url, 3);
            } else {
                $params = array();
            }

            $this->callModule($module, $controller, $method, $params);

        } else {

            $this->_hook->call('404');

            return;

        }

    }

    /**
     * Método que ejecuta el módulo\controlador\acción\parámetros parseado
     * @return void
     */
    public function callModule(
        $pModule = null, $pController = null, $pMethod = null, $pParams = array()
    )
    {

        // se valida que el parseo de la URL haya dado un módulo por resultado
        if (! $pModule || ! $pController) {
            $this->_hook->call('404');

            return;
        }

        // se valida si la ruta de la clase solicitada existe
        $path = 'app/modules/' . $pModule . '/controller/' . $pController . '.php';

        if (! file_exists($path)) {
            $this->_hook->call('404');

            return;
        } else {
            require $path;
        }

        // se agrega el namespace al controlador
        $pController = 'App\\Modules\\' . $pModule . '\\Controller\\' . $pController;

        // se valida si el método solicitado existe
        if (! method_exists($pController, $pMethod)) {
            $this->_hook->call('404');

            return;
        }

        // se instancia el controlador
        $controller = new $pController();

        // se ejecuta la acción junto a sus parámetros si existiesen
        call_user_func_array(array($controller, $pMethod), $pParams);

    }

}
