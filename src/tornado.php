<?php
namespace DMS\Tornado;

/**
 * Clase de principal/bootstrap del src
 *
 * @package TORNADO-CORE
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link http://tornado-php.com
 * @license http://tornado-php.com/licencia/ MIT License
 * @version 2.0.0-beta
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
     * Clase de ervicios inyectados
     * @var \DMS\Tornado\Service
     */
    private $_service = null;

    /**
     * Método constructor
     */
    private function __construct()
    {
        require __DIR__ . '/error.php';
        require __DIR__ . '/config.php';
        require __DIR__ . '/hook.php';
        require __DIR__ . '/route.php';
        require __DIR__ . '/service.php';
        require __DIR__ . '/annotation.php';
        require __DIR__ . '/controller.php';

        $this->_error = new Error();
        $this->_config = new Config([
            'tornado_environment_development' => true,
            'tornado_hmvc_use'                => false,
            'tornado_hmvc_module_path'        => '',
            'tornado_hmvc_serialize_path'     => ''
        ]);
        $this->_hook = new Hook();
        $this->_route = new Route();
        $this->_service = new Service();
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

        // se establece el gestor de errores
        $this->_error->setHandler(true);

        // se establecen comportamientos según el ambiente de la aplicación
        if ($this->_config['tornado_environment_development'] === true) {

            ini_set('display_errors', '1');
            error_reporting(E_ALL);

            if ($this->_config['tornado_environment_development'] === true)
                $this->_annotation->findRoutes(
                    $this->_config['tornado_hmvc_module_path'],
                    $this->_config['tornado_hmvc_serialize_path']
                );

        } else {

            ini_set('display_errors', '0');
            error_reporting(0);

        }

        // se registran las rutas serializadas de los controladores
        if ($this->_config['tornado_environment_development'] === true)
            $this->_route->unserialize($this->_config['tornado_hmvc_serialize_path']);

        // flujo de ejecución:
        // - se ejecutan los hooks init
        // - se parsea la url en busca de la ruta a ejecutar
        // - - si no hay coincidencias se ejecuta:
        // - - - hooks 404
        // - - - hooks end
        // - - - se finaliza
        // - se ejecutan los hooks before
        // - - si alguno devuelve false se ejecuta:
        // - - - hooks end
        // - - - se finaliza
        // - se ejecuta la ruta
        // - se ejecutan los hooks after
        // - se ejecutan los hooks end

        $this->_hook->call('init');

        $flowReturn = $this->_route->parseUrl();

        if ($flowReturn === false) {
            $this->_hook->call('404');
            $this->finishRequest();
            $this->_hook->call('end');
            return;
        }

        $flowReturn = $this->_hook->call('before');

        if ($flowReturn === false) {
            $this->finishRequest();
            $this->_hook->call('end');
            return;
        }

        $this->_route->execute();

        $this->_hook->call('after');
        $this->finishRequest();
        $this->_hook->call('end');
    }

    /**
     * Método que finaliza el request y continuar la ejecución del script
     */
    public function finishRequest()
    {
        if (function_exists('fastcgi_finish_request'))
            fastcgi_finish_request();
    }

    /**
     * Método que delega la petición a otra ruta
     * @param string $pUrl Ruta
     */
    public function forwardUrl($pUrl)
    {
        if ($this->_route->parseUrl($pUrl) !== true) {
            throw new \BadMethodCallException('Invalid url route.');
        }

        $this->_route->execute();
    }

    /**
     * Método que delega/ejecuta la petición a otro módulo
     * @param string $pModule Módulo
     * @param array  $pParams Parametros
     */
    public function forwardModule($pModule, $pParams = [])
    {
        $module = explode('|', $pModule);

        if (count($module) !== 3) {
            throw new \BadMethodCallException('Invalid module name.');
        }

        $this->_route->callModule($module[0], $module[1], $module[2], $pParams);
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
     * Método que agrega nuevos tipos de parámetros a los enrutamientos
     * @param string $pType       Abreviatura de parámetro
     * @param string $pExpression Expresión regular
     */
    public function addTypeParam($pType, $pExpression)
    {
        $this->_route->addType($pType, $pExpression);
    }

    /**
     * Método que asigna o recupera valores de configuración
     * @param  string $pNameArray  Nombre del valor de configuración o Array de configuración
     * @param  mixed  $pValue      Valor de configuración (puede ser un array)
     * @return mixed
     */
    public function config($pNameArray, $pValue = null)
    {
        if (func_num_args() === 1) {
            if (is_array($pNameArray))
                $this->_config->set($pNameArray);
            else
                return $this->_config[$pNameArray];
        }

        $this->_config[$pNameArray] = $pValue;
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

            return null;
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

            return null;
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

    /**
     * Método que retorna la ruta coincidente
     * @return array
     */
    public function getRouteMatch()
    {
        return $this->_route->getRouteMatch();
    }

    /**
     * Método que registra un servicio/clase externa
     * @param string $pService Nombre del servicio a registrar
     * @param callable $pCallback Función a ejecutar al momento de invocarse
     * @param bool $pEsSingleton Determina si el servicio debe registrarse como Singleton
     */
    public function register($pService, $pCallback, $pEsSingleton = false)
    {
        if ($pEsSingleton === true)
            $this->_service->registerSingleton($pService, $pCallback);
        else
            $this->_service->register($pService, $pCallback);
    }

    /**
     * Método que devuelve un servicio/parámetro
     * @param $pService String Nombre del servicio/parámetro
     * @return mixed
     */
    public function container($pService)
    {
        return $this->_service->get($pService);
    }
}
