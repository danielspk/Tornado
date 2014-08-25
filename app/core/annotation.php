<?php
namespace DMS\Tornado;

/**
 * Clase de anotaciones DocBlocks
 *
 * @package TORNADO-CORE
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link http://tornado-php.com
 * @license http://tornado-php.com/licencia/ MIT License
 * @version 0.9.9
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

            include $file;

            $nameClass = str_replace(array('/', '.php'), array('\\', ''), $file);

            $rc = new \ReflectionClass($nameClass);

            $methods = $rc->getMethods();

            // se recorren los métodos del controlador
            foreach ($methods as $method) {

                $rm = new \ReflectionMethod($nameClass, $method->name);

                $namespaceSections = explode('\\', $nameClass);
                $commentsText = $rm->getDocComment();
                $commentsLines = explode("\n", $commentsText);

                // se recuperan los tags de enrutamientos
                $routes = array_filter($commentsLines, function ($value) {
                    return (strpos($value, '@T_ROUTE') !== false);
                });

                // se agregan los enrutamientos
                foreach ($routes as $route) {

                    $route = trim(substr(trim(str_replace('@T_ROUTE', '', $route)), 1));
                    $callback = $namespaceSections[2] . '|' . $namespaceSections[4] . '|' . $method->name;

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
