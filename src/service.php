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
    private $services = [];

    /**
     * Parámetros de servicios
     * @var array
     */
    private $parameters = [];

    /**
     * Método que registra un servicio/parámetro
     * @param string $pService Nombre del servicio/parámetro a registrar
     * @param callable $pCallback Función a ejecutar o parámetro de servicio
     */
    public function register($pService, $pCallback)
    {
        if (is_callable($pCallback))
            $this->services[$pService] = $pCallback;
        else
            $this->parameters[$pService] = $pCallback;
    }

    /**
     * Método que registra un servicio/clase externa como Singleton
     * @param string $pService Nombre del servicio a registrar
     * @param callable $pCallback Función a ejecutar al momento de invocarse
     */
    public function registerSingleton($pService, $pCallback)
    {
        $this->services[$pService] = function () use ($pService, $pCallback) {

            static $instance;

            if (null === $instance) {
                $instance = $pCallback();
            }

            return $instance;
        };
    }

    /**
     * Método que recupera un servicio/parámetro inyectado
     * @param string $pService Nombre del servicio/parámetro
     * @return mixed
     */
    public function get($pService)
    {
        if (isset($this->parameters[$pService]))
            return $this->parameters[$pService];

        if (!isset($this->services[$pService])) {
            throw new \BadMethodCallException('The service ' . $pService . ' is not registered in Tornado.');
        }

        return $this->services[$pService]($this);
    }
}
