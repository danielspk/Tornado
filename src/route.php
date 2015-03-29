<?php
namespace DMS\Tornado;

/**
 * Clase de enrutamientos
 *
 * @package TORNADO-CORE
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link http://tornado-php.com
 * @license http://tornado-php.com/licencia/ MIT License
 * @version 2.0.0-beta
 */
final class Route
{
    /**
     * Contenedor de enrutamientos
     * @var array
     */
    private $_routes = [];

    /**
     * Tipos de parámetros soportados
     * @var array
     */
    private $_typesParams = [
        ':*'      => '(.*)',
        ':string' => '([a-zA-Z]+)',
        ':number' => '([0-9]+)',
        ':alpha'  => '([a-zA-Z0-9-_]+)',
        '[/'      => '/?',
        ']'       => '?'
    ];

    /**
     * Parámetros con nombre del enrutamiento invocado
     * @var array
     */
    private $_paramsName = null;

    /**
     * Índice de la ruta coincidente
     * @var null|int
     */
    private $_routeMatch = null;

    /**
     * Método que agrega nuevos tipos de parámetros
     * @param string $pType       Abreviatura de parámetro
     * @param string $pExpression Expresión regular
     */
    public function addType($pType, $pExpression)
    {
        $this->_typesParams[] = [$pType => $pExpression];
    }

    /**
     * Método que recupera un parámetro del enrutamiento actual
     * @param  string $pName Nombre
     * @return string
     */
    public function getParam($pName)
    {
        return (isset($this->_paramsName[$pName])) ? $this->_paramsName[$pName] : null;
    }

    /**
     * Método que retorna la ruta coincidente
     * @return array
     */
    public function getRouteMatch()
    {
        return $this->_routes[$this->_routeMatch];
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

            $this->_routes[] = [
                'method' => 'ALL',
                'route' => $pMethodRoute,
                'callback' => $pCallback,
                'params' => null
            ];

        } else {

            $route = trim(substr($pMethodRoute, $pos));
            $methods = trim(substr($pMethodRoute, 0, $pos));

            $this->_routes[] = [
                'method' => $methods,
                'route' => $route,
                'callback' => $pCallback,
                'params' => null
            ];
        }
    }

    /**
     * Método que registra rutas serializadas
     * @param $pSerializePath string Path de rutas serializadas
     */
    public function unserialize($pSerializePath)
    {
        $file = $pSerializePath . '/route_serialize.php';

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
     * @param null $pUrl Url a parsear
     * @return bool Resultado de la petición
     */
    public function parseUrl($pUrl = null)
    {
        // se ajustan las barras de la query string
        if ($pUrl === null)
            $querystring = (empty($_SERVER['QUERY_STRING'])) ? '/' : $_SERVER['QUERY_STRING'];
        else
            $querystring = $pUrl;

        $querystring .= (substr($querystring, -1) != '/') ? '/' : '';

        // método de petición
        $method = $_SERVER["REQUEST_METHOD"];

        // sobrescritura del método de petición si el navegador no soporta PUT y DELETE
        if ($method == 'POST' && isset($_POST['REST_METHOD']) && $_POST['REST_METHOD'] == 'PUT') {
            $method = 'PUT';
        } elseif ($method == 'POST' && isset($_POST['REST_METHOD']) && $_POST['REST_METHOD'] == 'DELETE') {
            $method = 'DELETE';
        }

        // se recorren las rutas registradas
        foreach ($this->_routes as $index => $route) {

            $routeMatch = strtr($route['route'], $this->_typesParams);  // se reemplazan los filtros de tipo de dato por expresiones
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
                            $this->_paramsName[$paramsNames[1][$i]] = $params[$i];
                        }

                    }

                } else {
                    $params = [];
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

                $this->_routeMatch = $index;
                $this->_routes[$index]['params'] = $params;

                return true;
            }

        }

        return false;
    }

    public function execute()
    {
        // se determina si hay una función anonima en vez de un módulo
        if (is_callable($this->_routes[$this->_routeMatch]['callback'])) {

            call_user_func_array($this->_routes[$this->_routeMatch]['callback'], $this->_routes[$this->_routeMatch]['params']);

        } else {

            $handler = explode('|', $this->_routes[$this->_routeMatch]['callback']);

            $this->callModule($handler[0], $handler[1], $handler[2], $this->_routes[$this->_routeMatch]['params']);

        }
    }

    /**
     * Método que ejecuta el módulo\controlador\acción\parámetros invocado
     * @param  string  $pModule     Nombre del módulo
     * @param  string  $pController Nombre del controlador
     * @param  string  $pMethod     Nombre del método
     * @param  array   $pParams     Parámetros del método
     * @return boolean Resultado de la invocación
     */
    public function callModule($pModule, $pController, $pMethod, $pParams = [])
    {
        // se valida si la ruta de la clase solicitada existe
        $path = 'app/modules/' . $pModule . '/controller/' . $pController . '.php';

        if (!file_exists($path)) {
            throw new \InvalidArgumentException('Module unknown.');
        } else {
            require_once $path;
        }

        // se agrega el namespace al controlador
        $pController = 'App\\Modules\\' . $pModule . '\\Controller\\' . $pController;

        // se valida si el método solicitado existe
        if (! method_exists($pController, $pMethod)) {
            throw new \InvalidArgumentException('Method unknown.');
        }

        // se instancia el controlador
        $controller = new $pController(Tornado::getInstance());

        // se ejecuta la acción junto a sus parámetros si existiesen
        call_user_func_array([$controller, $pMethod], $pParams);
    }
}
