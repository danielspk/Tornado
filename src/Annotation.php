<?php
namespace DMS\Tornado;

/**
 * Clase de anotaciones DocBlocks
 *
 * @package TORNADO-PHP
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link http://tornado-php.com
 * @license http://tornado-php.com/licencia/ MIT License
 * @version 2.0.0-beta
 */
final class Annotation
{
    /**
     * Método que busca anotaciones de enrutamientos y serializa su resultado
     * @param $pHmvcPath      string Path de módulos hmvc
     * @param $pSerializePath string Path de rutas serializadas
     * @return void
     */
    public function findRoutes($pHmvcPath, $pSerializePath)
    {
        $routesFind = [];

        // se recorren los controladores con biblioteca SPL
        // (no se lleva a cabo con \GlobIterator dado que en Windows sólo funciona con rutas absolutas)
        $controllers = new \RegexIterator(
            new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(
                    $pHmvcPath,
                    \RecursiveDirectoryIterator::SKIP_DOTS |
                    \RecursiveDirectoryIterator::UNIX_PATHS
                )
            ),
            '/.*\/Controller\/.*\.php/i'
        );

        foreach($controllers as $file) {

            require $file;

            $namespaceClass = str_replace(['/', '.php'], ['\\', ''], $file);
            $namespaceSections = array_reverse(explode('\\', $namespaceClass));

            $rc = new \ReflectionClass($namespaceClass);

            $methods = $rc->getMethods();

            // se recorren los métodos del controlador
            foreach ($methods as $method) {

                $rm = new \ReflectionMethod($namespaceClass, $method->name);

                $commentsText = $rm->getDocComment();
                $commentsLines = explode("\n", $commentsText);

                // se recuperan los tags de enrutamientos
                $routes = array_filter($commentsLines, function ($value) {
                    return (strpos($value, '@T_ROUTE') !== false);
                });

                // se agregan los enrutamientos
                foreach ($routes as $route) {

                    $route = trim(substr(str_replace('@T_ROUTE', '', trim($route)), 1));
                    $callback = $namespaceSections[2] . '|' . $namespaceSections[0] . '|' . $method->name;

                    $routesFind[] = [$route, $callback];

                }

            }

        }

        // se serializan los enrutamientos en un archivo de configuración
        if (count($routesFind) > 0) {
            $sz = serialize($routesFind);
            file_put_contents($pSerializePath . '/serialized.php', $sz);
        }
    }
}
