<?php
namespace DMS\Tornado;

/**
 * Clase de configuracion
 *
 * @package TORNADO-PHP
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
    private $config;

    /**
     * Método constructor
     * @param array $pConf Configuración inicial
     */
    public function __construct($pConf = [])
    {
        $this->config = $pConf;
    }

    /**
     * Método que setea un array de configuración
     * @param $pArray
     */
    public function set($pArray)
    {
        $this->config = array_merge($this->config, $pArray);
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
            $this->config[$pName] = $pValue;
        }
    }

    /**
     * Método que evalua si un identificador de configuración existe (Interfase \ArrayAccess)
     * @param  string $pName Identificador
     * @return bool
     */
    public function offsetExists($pName)
    {
        return isset($this->config[$pName]);
    }

    /**
     * Método que elimina una configuración (Interfase \ArrayAccess)
     * @param string $pName Identificador
     */
    public function offsetUnset($pName)
    {
        unset($this->config[$pName]);
    }

    /**
     * Método que devuelve valores de configuración (Interfase \ArrayAccess)
     * @param  string $pName Identificador
     * @return mixed
     */
    public function offsetGet($pName)
    {
        return isset($this->config[$pName]) ? $this->config[$pName] : null;
    }
}
