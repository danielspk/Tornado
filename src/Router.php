<?php
namespace DMS\Tornado;

/**
 * Clase de enrutamientos
 *
 * @package TORNADO-PHP
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link http://tornado-php.com
 * @license http://tornado-php.com/licencia/ MIT License
 * @version 2.0.0
 */
final class Router
{
    /**
     * Ubicación de los módulos HMVC
     * @var string
     */
    private $pathModules;

    /**
     * Contenedor de enrutamientos
     * @var array
     */
    private $routes = [];

    /**
     * Tipos de parámetros soportados
     * @var array
     */
    private $typesParams = [
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
    private $paramsName = null;

    /**
     * Índice de la ruta coincidente
     * @var null|int
     */
    private $routeMatch = null;

    /**
     * Método que setea el path de los módulos HMVC
     * @param $pPath Path de módulos HMVC
     */
    public function setPathModules($pPath)
    {
        $this->pathModules = $pPath;
    }

    /**
     * Método que agrega nuevos tipos de parámetros
     * @param string $pType       Abreviatura de parámetro
     * @param string $pExpression Expresión regular
     */
    public function addType($pType, $pExpression)
    {
        $this->typesParams[] = [$pType => $pExpression];
    }

    /**
     * Método que recupera un parámetro del enrutamiento actual
     * @param  string $pName Nombre
     * @return string
     */
    public function getParam($pName)
    {
        return (isset($this->paramsName[$pName])) ? $this->paramsName[$pName] : null;
    }

    /**
     * Método que retorna la ruta coincidente
     * @return array
     */
    public function getRouteMatch()
    {
        return $this->routes[$this->routeMatch];
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

            $this->routes[] = [
                'method' => 'ALL',
                'route' => $pMethodRoute,
                'callback' => $pCallback,
                'params' => null
            ];

        } else {

            $route = trim(substr($pMethodRoute, $pos));
            $methods = trim(substr($pMethodRoute, 0, $pos));

            $this->routes[] = [
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
        $file = $pSerializePath . '/serialized.php';

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
        foreach ($this->routes as $index => $route) {

            $routeMatch = strtr($route['route'], $this->typesParams);  // se reemplazan los filtros de tipo de dato por expresiones
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
                            $this->paramsName[$paramsNames[1][$i]] = $params[$i];
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

                $this->routeMatch = $index;
                $this->routes[$index]['params'] = $params;

                return true;
            }

        }

        return false;
    }

    /**
     * Método que ejecuta la ruta parseada
     */
    public function execute()
    {
        // se determina si hay una función anonima en vez de un módulo
        if (is_callable($this->routes[$this->routeMatch]['callback'])) {

            call_user_func_array($this->routes[$this->routeMatch]['callback'], $this->routes[$this->routeMatch]['params']);

        } else {

            $handler = explode('|', $this->routes[$this->routeMatch]['callback']);

            $this->callModule($handler[0], $handler[1], $handler[2], $this->routes[$this->routeMatch]['params']);

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
        // se ajustan los nombres
        $pModule     = $this->parseModuleName($pModule);
        $pController = $this->parseModuleName($pController);
        $pMethod     = lcfirst($this->parseModuleName($pMethod));

        // se valida si la ruta de la clase solicitada existe
        $path = $this->pathModules.  '/' . $pModule . '/Controller/' . $pController . '.php';

        if (!file_exists($path)) {
            throw new \InvalidArgumentException('Module or Controller unknown.');
        } //else {
            //require_once $path; // Autoload PSR-4
        //}

        // se agrega el namespace al controlador
        $pController = $this->parseNamespace($this->pathModules) . '\\' . $pModule . '\\Controller\\' . $pController;

        // se valida si el método solicitado existe
        if (! method_exists($pController, $pMethod)) {
            throw new \InvalidArgumentException('Method unknown.');
        }

        // se instancia el controlador
        $controller = new $pController(Tornado::getInstance());

        // se ejecuta la acción junto a sus parámetros si existiesen
        call_user_func_array([$controller, $pMethod], $pParams);
    }

    /**
     * Método que pasear el path del módulo en un namespace psr-4 válido
     * Ejemplo: app/modules => App\\Modules
     * @param $pPath Path donde se ubica el módulo
     * @return string
     */
    private function parseNamespace($pPath)
    {
        $words = ucwords(str_replace('/', ' ', $pPath));
        return str_replace(' ', '\\', $words);
    }

    /**
     * Método que parsea un nombre del formato url al formato real de archivo
     * Ejemplo: procesar-login => ProcesarLogin
     * @param $pName Nombre del módulo, controllador o método
     * @return string
     */
    private function parseModuleName($pName)
    {
        $words = ucwords(str_replace(array('-', '_'), ' ', $pName));
        return str_replace(' ', '', $words);
    }
}
