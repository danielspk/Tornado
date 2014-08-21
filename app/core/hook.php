<?php
namespace DMS\Tornado;

/**
 * Clase de eventos/ganchos para extender el core
 *
 * @package TORNADO-CORE
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link http://tornado-php.com
 * @license http://tornado-php.com/licencia/ MIT License
 * @version 0.9.7
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
     * @param string $pName     Nombre del evento
     * @param mixed  $pCallback Array con clase::método o función callable
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
        if (!isset($this->_hooks[$pName])) {
            throw new \InvalidArgumentException('Hook no registrado.');
        }

        // si el callback del hook es un array se hace una llamada a la clase/método
        if (is_array($this->_hooks[$pName])) {

            $classHook = new $this->_hooks[$pName][0]();

            call_user_func_array(
                array(
                    $classHook,
                    $this->_hooks[$pName][1]
                ),
                $this->_hooks[$pName][2]
            );

        // si el callback es una función anónima se la ejecuta
        } elseif (is_callable($this->_hooks[$pName])) {

            call_user_func($this->_hooks[$pName]);

        }

    }

}
