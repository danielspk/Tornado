<?php
namespace DMS\Tornado;

/**
 * Clase base de controladores
 *
 * @package TORNADO-PHP
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link http://tornado-php.com
 * @license http://tornado-php.com/licencia/ MIT License
 * @version 2.0.0-beta
 */
abstract class Controller
{
    /**
     * Instancia de Tornado (singleton)
     * @var \DMS\Tornado\Tornado
     */
    protected $app;

    /**
     * Contructor de la Clase
     *
     * @param \DMS\Tornado\Tornado $pApp Instancia de Tornado
     */
    public function __construct(Tornado $pApp)
    {
        $this->app = $pApp;
    }
}
