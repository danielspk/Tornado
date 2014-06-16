<?php
namespace DMS\Tornado;

/**
 * Clase de eventos/ganchos para extender el core
 *
 * @package TORNADO-CORE
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
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

        // si el callback del hook es un string se hace una llamada a un módulo
        if (is_string($this->_hooks[$pName])) {

            // se obtiene el modulo controlador método
            $handler = explode('\\', $this->_hooks[$pName]);

            $app = Tornado::getInstance();
            $app->callModule($handler[0], $handler[1], $handler[2]);

        // si el callback es una función anónima se la ejecuta
        } elseif (is_callable($this->_hooks[$pName])) {
            
            call_user_func($this->_hooks[$pName]);
            
        }

    }

}
