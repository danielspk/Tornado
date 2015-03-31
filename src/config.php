<?php
namespace DMS\Tornado;

/**
 * Clase de configuracion
 *
 * @package TORNADO-CORE
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link http://tornado-php.com
 * @license http://tornado-php.com/licencia/ MIT License
 * @version 2.0.0-beta
 */
final class Config implements \ArrayAccess
{
    /**
     * Matriz con configuración dinámica
     * @var array
     */
    private $_config;

    /**
     * Método constructor
     * @param array $pConf Configuración inicial
     */
    public function __construct($pConf = [])
    {
        $this->_config = $pConf;
    }

    /**
     * Método que setea valores de configuración (Interfase \ArrayAccess)
     * @param  string                    $pName  Identificador
     * @param  mixed                     $pValue Valor
     * @throws \InvalidArgumentException
     */
    public function offsetSet($pName, $pValue)
    {
        if (is_null($pName)) {
            throw new \InvalidArgumentException('Assignment of value without identifier.');
        } else {
            $this->_config[$pName] = $pValue;
        }
    }

    /**
     * Método que evalua si un identificador de configuración existe (Interfase \ArrayAccess)
     * @param  string $pName Identificador
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
     * @param  string $pName Identificador
     * @return mixed
     */
    public function offsetGet($pName)
    {
        return isset($this->_config[$pName]) ? $this->_config[$pName] : null;
    }
}