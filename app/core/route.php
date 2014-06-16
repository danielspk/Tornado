<?php
namespace DMS\Tornado;

/**
 * Clase de rutas
 *
 * @package TORNADO-CORE
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link https://github.com/danielspk/TORNADO
 * @license https://github.com/danielspk/TORNADO/blob/master/LICENSE MIT License
 * @version 0.9.0
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
     * Método que registra un evento de aplicación
     * @param string $pMethod   Método de petición
     * @param string $pRoute    Patrón de la ruta
     * @param mixed  $pCallback Módulo o función a invocar
     */
    public function register($pMethod, $pRoute, $pCallback)
    {
        $this->_routes[] = array(
            'method' => $pMethod,
            'route' => $pRoute,
            'callback' => $pCallback)
        ;
    }

}
