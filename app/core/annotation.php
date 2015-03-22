<?php
namespace DMS\Tornado;

/**
 * Clase de anotaciones DocBlocks
 *
 * @package TORNADO-CORE
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link http://tornado-php.com
 * @license http://tornado-php.com/licencia/ MIT License
 * @version 1.0.0
 */
final class Annotation
{
    /**
     * Método que busca anotaciones de enrutamientos y serializa su resultado
     * @return void
     */
    public function findRoutes()
    {
        $routesFind = array();

        // se recorren los controladores
        foreach (glob('app/modules/*/controller/*.php') as $file) {

            require $file;

            $nameClass = str_replace(array('/', '.php'), array('\\', ''), $file);
            $namespaceSections = explode('\\', $nameClass);
            $moduleSections = $namespaceSections[2] . '|' . $namespaceSections[4] . '|';

            $rc = new \ReflectionClass($nameClass);

            $methods = $rc->getMethods();

            // se recorren los métodos del controlador
            foreach ($methods as $method) {

                $rm = new \ReflectionMethod($nameClass, $method->name);

                $commentsText = $rm->getDocComment();
                $commentsLines = explode("\n", $commentsText);

                // se recuperan los tags de enrutamientos
                $routes = array_filter($commentsLines, function ($value) {
                    return (strpos($value, '@T_ROUTE') !== false);
                });

                // se agregan los enrutamientos
                foreach ($routes as $route) {

                    $route = trim(substr(str_replace('@T_ROUTE', '', trim($route)), 1));
                    $callback = $moduleSections . $method->name;

                    $routesFind[] = array($route, $callback);

                }

            }

        }

        // se serializan los enrutamientos en un archivo de configuración
        if (count($routesFind) > 0) {
            $sz = serialize($routesFind);
            file_put_contents(__DIR__ . '/../config/route_serialize.php', $sz);
        }
    }
}
