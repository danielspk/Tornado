<?php
namespace DMS\Tornado;

/**
 * Clase de eventos/ganchos para extender el core
 *
 * @package TORNADO-CORE
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link http://tornado-php.com
 * @license http://tornado-php.com/licencia/ MIT License
 * @version 1.0.0
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
     * @param int    $pPosition Orden de ejecución del hook
     */
    public function register($pName, $pCallback, $pPosition)
    {
        if ($pPosition !== null)
            $this->_hooks[$pName][$pPosition] = $pCallback;
        else
            $this->_hooks[$pName][] = $pCallback;
    }

    /**
     * Método que ejecuta un evento de aplicación
     * @param string $pName Nombre del evento
     * @return mixed|null
     */
    public function call($pName)
    {
        $return = null;

        if (!isset($this->_hooks[$pName])) {
            if (in_array($pName, array('init', 'before', 'after', 'end', 'error', '404')))
                return $return;
            else
                throw new \InvalidArgumentException('Not registered Hook.');
        }

        // se ordenan los hooks con ese nombre
        ksort($this->_hooks[$pName]);

        // se recorren los hooks con ese nombre
        foreach ($this->_hooks[$pName] as $hook) {

            // si el callback del hook es un array se hace una llamada a la clase/método
            if (is_array($hook)) {

                $classHook = new $hook[0]();

                $return = call_user_func_array(
                    array(
                        $classHook,
                        $hook[1]
                    ),
                    $hook[2]
                );

                // si el callback es una función anónima se la ejecuta
            } elseif (is_callable($hook)) {

                $return = call_user_func($hook);

            }

            // si un hook devuelve false se impide la ejecución de los siguientes hooks
            if ($return === false)
                return $return;
        }
    }
}
