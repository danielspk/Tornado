<?php
namespace DMS\Tornado;

/**
 * Clase de servicios (inyector de dependencias)
 *
 * @package TORNADO-CORE
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link http://tornado-php.com
 * @license http://tornado-php.com/licencia/ MIT License
 * @version 2.0.0-beta
 */
final class Service
{
    /**
     * Servicios inyectados
     * @var array
     */
    private $_services = [];

    /**
     * Método que registra un servicio/clase externa
     * @param string $pService Nombre del servicio a registrar
     * @param callable $pCallback Función a ejecutar al momento de invocarse
     */
    public function register($pService, $pCallback)
    {
        $this->_services[$pService] = $pCallback;
    }

    /**
     * Método que registra un servicio/clase externa como Singleton
     * @param string $pService Nombre del servicio a registrar
     * @param callable $pCallback Función a ejecutar al momento de invocarse
     */
    public function registerSingleton($pService, $pCallback)
    {
        $this->_services[$pService] = function () use ($pService, $pCallback) {

            static $instance;

            if (null === $instance) {
                $instance = $pCallback();
            }

            return $instance;
        };
    }

    /**
     * Método que recupera un servicio inyectado
     * @param string $pService Nombre del servicio
     * @param array $pArgs Parámetros
     * @return object
     */
    public function get($pService, $pArgs = [])
    {
        if (!isset($this->_services[$pService])) {
            throw new \BadMethodCallException('The service ' . $pService . ' is not registered in Tornado.');
        }

        if (count($pArgs))
            return call_user_func_array($this->_services[$pService], $pArgs);
        else
            return $this->_services[$pService]();
    }
}
