<?php
namespace DMS\Tornado;

/**
 * Clase de eventos/ganchos para extender el core
 * 
 * @package TORNADO-CORE
 * @author Daniel Martín Spiridione <info@daniel-spiridione.com.ar>
 * @link https://github.com/danielspk/TORNADO
 * @license https://github.com/danielspk/TORNADO/blob/master/LICENSE MIT License
 * @version 0.9.0
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
	 * @todo Si $this->_hooks[$pName] incluye @ incluir archivo de módulo
	 */
	public function call($pName)
	{
		if (!isset($this->_hooks[$pName])) {
			throw new \InvalidArgumentException('Hook no registrado.');
		}
		
		if (is_string($pName)) {
			
			$handler = explode('\\', $pName);
			
			$path = 'app/modules/' . $handler[0] . '/controller/' . $handler[1] . '.php';
			
			if (! file_exists($path)) {
				$this->call('404');
				return;
			}
		
			$controllerNam = 'App\\Modules\\' . $handler[1];
			
			if (! method_exists($controllerNam, $handler[2])) {
				$this->call('404');
				return;
			}

			$controller = new $controllerNam();
		
			call_user_func_array(array($controller, $handler[2]));
			
			return;
			
		}
		
		if (is_callable($this->_hooks[$pName])) {
			call_user_func($this->_hooks[$pName]);
		}
	}

}