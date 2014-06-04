<?php
namespace DMS\Tornado;

/**
 * Clase de configuracion
 * 
 * @package TORNADO-CORE
 * @author Daniel Martín Spiridione <info@daniel-spiridione.com.ar>
 * @link https://github.com/danielspk/TORNADO
 * @license https://github.com/danielspk/TORNADO/blob/master/LICENSE MIT License
 * @version 0.9.0
 */
final class Config implements \ArrayAccess
{
	
	/**
	 * Matriz con configuracion dinámica
	 * @var array 
	 */
	private $_config = array();
	
	/**
	 * Método que setea valores de configuración (Interfase \ArrayAccess)
	 * @param string $pName Identificador
	 * @param mixed $pValue Valor
	 * @throws \InvalidArgumentException
	 */
	public function offsetSet($pName, $pValue)
	{
		if (is_null($pName)) {
			throw new \InvalidArgumentException('No se permite setear valores sin un identificador.');
		} else {
			$this->_config[$pName] = $pValue;
		}
	}
	
	/**
	 * Método que evalua si un identificador de configuración existe (Interfase \ArrayAccess)
	 * @param string $pName Identificador
	 * @return bool
	 */
	public function offsetExists($pName)
	{
		return isset($this->_config[$pName]);
	}
	
	/**
	 * Método que elimina una configuración (Interfase \ArrayAccess)
	 * @param string $pName Identificador
	 */
	public function offsetUnset($pName)
	{
		unset($this->_config[$pName]);
	}
	
	/**
	 * Método que devuelve valores de configuración (Interfase \ArrayAccess)
	 * @param string $pName Identificador
	 * @return mixed
	 */
	public function offsetGet($pName)
	{
		return isset($this->_config[$pName]) ? $this->_config[$pName] : null;
	}
	
}