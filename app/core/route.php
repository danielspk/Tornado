<?php
namespace DMS\Core;

/**
 * Clase de rutas
 * 
 * @package DMS-TORNADO-CORE
 * @author Daniel Martín Spiridione <info@daniel-spiridione.com.ar>
 * @link https://github.com/danielspk/DMS-PHP-CORE
 * @license https://github.com/danielspk/DMS-PHP-CORE/blob/master/LICENSE MIT
 * @version 0.8.0
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
	 * @param string $pMethod Método de petición
	 * @param string $pRoute Patrón de la ruta
	 * @param mixed $pCallback Módulo o función a invocar
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