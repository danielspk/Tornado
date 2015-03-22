<?php
namespace DMS\Tornado;

/**
 * Clase de servicios (inyector de dependencias)
 *
 * @package TORNADO-CORE
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link http://tornado-php.com
 * @license http://tornado-php.com/licencia/ MIT License
 * @version 1.0.0
 */
final class Service
{
    /**
     * Servicios inyectados
     * @var array
     */
    private $_services = array();

    /**
     * Método que registra un servicio/clase externa
     * @param string   $pService  Nombre del servicio a registrar
     * @param callable $pCallback Función a ejecutar al momento de invocarse
     */
    public function register($pService, $pCallback)
    {
        $this->_services[$pService] = $pCallback;
    }

    /**
     * Método mágico __call. Se asume que es invocado al solicitar un servicio inyectado
     * @param  string $pService Nombre del servicio
     * @param  array  $pArgs    Parámetros
     * @return object
     */
    public function __call($pService, $pArgs)
    {
        if (!isset($this->_services[$pService])) {
            throw new \BadMethodCallException('The service ' . $pService . ' is not registered.');
        }

        return call_user_func_array($this->_services[$pService], $pArgs);
    }

    /**
     * Método mágico __get. Se asume que es invocado al solicitar un servicio inyectado
     * @param  string $pService Nombre del servicio
     * @return object
     */
    public function __get($pService)
    {
        if (!isset($this->_services[$pService])) {
            throw new \InvalidArgumentException('The service ' . $pService . ' is not registered.');
        }

        return $this->_services[$pService]();
    }
}