<?php
namespace DMS\Tornado;

/**
 * Clase de principal/bootstrap del core
 *
 * @package TORNADO-CORE
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link http://tornado-php.com
 * @license http://tornado-php.com/licencia/ MIT License
 * @version 0.9.8
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
     * Clase de manejo de anotaciones
     * @var DMS\Tornado\Annotation
     */
    private $_annotation = null;

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

        // se ejecuta el hook de inicio
        $this->_hook->call('init');

        // se carga el modulo/callback correspondiente a la URL del request
        if ($this->_route->invokeUrl() === false) {
            $this->_hook->call('404');
        }

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

        require $pTemplate;
    }
    
}
