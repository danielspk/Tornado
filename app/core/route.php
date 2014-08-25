<?php
namespace DMS\Tornado;

/**
 * Clase de enrutamientos
 *
 * @package TORNADO-CORE
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link http://tornado-php.com
 * @license http://tornado-php.com/licencia/ MIT License
 * @version 0.9.9
 */
final class Route
{

    /**
     * Contenedor de enrutamientos
     * @var array
     */
    private $_routes = array();

    /**
     * Parámetros del enrutamiento invocado
     * @var array
     */
    private $_params = null;

    /**
     * Método que recupera un parámetro del enrutamiento actual
     * @param  string $pName Nombre
     * @return string
     */
    public function getParam($pName)
    {
        return (isset($this->_params[$pName])) ? $this->_params[$pName] : null;
    }

    /**
     * Método que registra una ruta, sus métodos de invocación y su callback
     * @param string $pMethodRoute Método de petición y Patrón de ruta
     * @param mixed  $pCallback    Módulo o función a invocar
     */
    public function register($pMethodRoute, $pCallback)
    {

        $pos = strpos(trim($pMethodRoute), '/');

        if ($pos === 0) {

            $this->_routes[] = array(
                'method' => 'ALL',
                'route' => $pMethodRoute,
                'callback' => $pCallback
            );

        } else {

            $route = trim(substr($pMethodRoute, $pos));
            $methods = trim(substr($pMethodRoute, 0, $pos));

            $this->_routes[] = array(
                'method' => $methods,
                'route' => $route,
                'callback' => $pCallback
            );

        }

    }

    /**
     * Método que registra rutas serializadas
     */
    public function unserialize()
    {

        $file = __DIR__ . '/../config/route_serialize.php';

        if (file_exists($file)) {

            $sz = file_get_contents($file);
            $serialized = unserialize($sz);

            foreach ($serialized as $route) {
                $this->register($route[0], $route[1]);
            }

        }

    }

    /**
     * Método que parsea la url en busca del módulo/callback a ejecutar
     * @return boolean Resultado de la petición
     */
    public function invokeUrl()
    {

        $app = Tornado::getInstance();

        // se determina si la URL esta enrutada hacia un módulo

        // se ajustan las barras de la query string
        $querystring = (empty($_SERVER['QUERY_STRING'])) ? '/' : $_SERVER['QUERY_STRING'];
        $querystring .= (substr($querystring, -1) != '/') ? '/' : '';

        // filtros de enrutadores y expresión resultante
        $tokens = array(
            ':*'         => '(.*)',
            ':string'    => '([a-zA-Z]+)',
            ':number'    => '([0-9]+)',
            ':alpha'     => '([a-zA-Z0-9-_]+)',
            '[/' => '/?',
            ']' => '?'
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
        foreach ($this->_routes as $route) {

            $routeMatch = strtr($route['route'], $tokens);  // se reemplazan los filtros de tipo de dato por expresiones
            $routeMatch = preg_replace('#@([a-zA-Z0-9-_]+)#', '', $routeMatch); // se eliminan los nombres de parámetros

            if (
                ($route['method'] == 'ALL' || strstr($route['method'], $method) !== false) &&
                preg_match('#^/?' . $routeMatch . '/?$#', $querystring, $matches)
            ) {

                // se determina si hay parámetros
                if (count($matches) > 1) {

                    $params = array_slice($matches, 1);

                    // se obtienen los nombres de parámetros
                    preg_match_all('#@([a-zA-Z0-9-_]+):#', $route['route'], $paramsNames);

                    if (count($paramsNames)) {

                        $cantP = count($paramsNames[1]);

                        for ($i = 0; $i < $cantP; $i++) {
                            $this->_params[$paramsNames[1][$i]] = $params[$i];
                        }

                    }

                } else {
                    $params = array();
                }

                // el comodin :* hace que la expresión regular no separe los
                // parámetros de la url
                if (count($params) && strpos(end($params), '/') !== false) {

                    // se extran los parámetros del comodin
                    $paramAsterik = explode('/', trim(end($params), '/'));

                    // se elimina el último parámetro (comodin)
                    array_pop($params);

                    // se unen los parámetros
                    $params = array_merge($params, $paramAsterik);

                }

                // se determina si hay una función anonima en vez de un módulo
                if (is_callable($route['callback'])) {

                    call_user_func_array($route['callback'], $params);

                    return true;

                } else {

                    $handler = explode('|', $route['callback']);

                    return $this->_callModule($handler[0], $handler[1], $handler[2], $params);

                }

            }

        }

        // si la URL no fue enrutada y no se deshabilito el acceso a hmvc desde
        // URLs se parsea la misma en busca de un módulo\controlador\método\parámetros
        if ($_SERVER['QUERY_STRING'] && $app->config('tornado_url_hmvc_deny') != true) {

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

            return $this->_callModule($module, $controller, $method, $params);

        }

        return false;

    }

    /**
     * Método que ejecuta el módulo\controlador\acción\parámetros invocado
     * @param  string  $pModule     Nombre del módulo
     * @param  string  $pController Nombre del controlador
     * @param  string  $pMethod     Nombre del método
     * @param  array   $pParams     Parámetros del método
     * @return boolean Resultado de la invocación
     */
    private function _callModule(
        $pModule = null, $pController = null, $pMethod = null, $pParams = array()
    )
    {

        // se valida que el parseo de la URL haya dado un módulo por resultado
        if (! $pModule || ! $pController) {
            return false;
        }

        // se valida si la ruta de la clase solicitada existe
        $path = 'app/modules/' . $pModule . '/controller/' . $pController . '.php';

        if (! file_exists($path)) {
            return false;
        } else {
            require_once $path;
        }

        // se agrega el namespace al controlador
        $pController = 'App\\Modules\\' . $pModule . '\\Controller\\' . $pController;

        // se valida si el método solicitado existe
        if (! method_exists($pController, $pMethod)) {
            return false;
        }

        // se instancia el controlador
        $controller = new $pController();

        // se ejecuta la acción junto a sus parámetros si existiesen
        call_user_func_array(array($controller, $pMethod), $pParams);

        return true;

    }

}
