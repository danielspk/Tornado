<?php
namespace DMS\Tornado;

/**
 * Clase de rutas
 *
 * @package TORNADO-CORE
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link http://tornado-php.com
 * @license https://github.com/danielspk/TORNADO/blob/master/LICENSE MIT License
 * @version 0.9.7
 */
final class Route
{

    /**
     * Contenedor de funciones de eventos de usuario
     * @var array
     */
    private $_routes = array();

    /**
     * Método que devuelve todas las rutas
     * @return array
     */
    public function getRoutes()
    {
        return $this->_routes;
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

}
