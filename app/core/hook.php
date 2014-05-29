<?php
namespace DMS\Core;

/**
 * Clase de eventos/ganchos para extender el core
 * 
 * @package DMS-TORNADO-CORE
 * @author Daniel Martín Spiridione <info@daniel-spiridione.com.ar>
 * @link https://github.com/danielspk/DMS-PHP-CORE
 * @license https://github.com/danielspk/DMS-PHP-CORE/blob/master/LICENSE MIT
 * @version 0.8.0
 */
final class Hook
{
	
	/**
	 * Contenedor de funciones de eventos de usuario
	 * @var array 
	 */
	private $_hooks = array();
	
	/**
	 * Método que registra un evento de aplicación
	 * @param string $pName Nombre del evento
	 * @param mixed $pCallback Array con clase::método o función callable
	 */
	public function register($pName, $pCallback)
	{
		$this->_hooks[$pName] = $pCallback;
	}
	
	/**
	 * Método que ejecuta un evento de aplicación
	 * @param string $pName Nombre de evento
	 */
	public function call($pName)
	{
		if (isset($this->_hooks[$pName]) && is_callable($this->_hooks[$pName])) {
			call_user_func($this->_hooks[$pName]);
		}
	}

}