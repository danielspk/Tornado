<?php
namespace DMS\Tornado;

/**
 * Clase de manejo de errores y excepciones
 *
 * @package TORNADO-CORE
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link https://github.com/danielspk/TORNADO
 * @license https://github.com/danielspk/TORNADO/blob/master/LICENSE MIT License
 * @version 0.9.6
 */
final class Error
{

    /**
     * Excepción capturada
     * @var type Exception
     */
    private $_currentException;

    /**
     * Método constructor
     */
    public function __construct()
    {
        $this->setHandler();
    }

    /**
     * Método que crea la instancia de la clase si no existiese
     * Si la instancia no existe se encarga de registrar los manejadores
     * de errores y excepciones
     * @param  boolean $pEnable Indica si debe habilitarse o deshabilitarse
     * @return void
     */
    public function setHandler($pEnable = true)
    {
        if ($pEnable === true) {
            set_error_handler(array($this, 'handlerError'));
            set_exception_handler(array($this, 'handlerException'));
        } else {
            restore_error_handler();
            restore_exception_handler();
        }
    }

    /**
     * Método que devuelve la excepción actual
     * @return Exception
     */
    public function getCurrentException()
    {
        return $this->_currentException;
    }

    /**
     * Método que gestiona los errores y los convierte en exepciones
     * @param  int            $pErrNro  Número del error
     * @param  string         $pErrStr  Descripción del error
     * @param  string         $pErrFile Archivo del error
     * @param  int            $pErrLine Número de línea del error
     * @return boolean|void
     * @throws ErrorException
     */
    public function handlerError($pErrNro, $pErrStr, $pErrFile, $pErrLine)
    {

        // Se omiten los errores del tipo E_NOTICE
        if ($pErrNro === E_NOTICE) {
            return false;
        }

        // Se genera una excepción
        throw new \ErrorException($pErrStr, $pErrNro, 1, $pErrFile, $pErrLine);

    }

    /**
     * Método que gestiona las excepciones
     * @param  ErrorException $pExc Excepción
     * @return void
     */
    public function handlerException($pExc)
    {
        // se desactiva el manejador de errores por si hay un
        // nuevo error en el hook de errores (recursividad infinita)
        $this->setHandler(false);

        // se conserva la excepción
        $this->_currentException = $pExc;

        // se limpia el buffer de salida
        ob_clean();

        // se ejecuta el gancho de errores
        Tornado::getInstance()->hook('error');

        // se finaliza la ejecución
        exit();

    }

}
