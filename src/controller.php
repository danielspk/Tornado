<?php
namespace DMS\Tornado;

/**
 * Clase base de controladores
 *
 * @package TORNADO-CORE
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link http://tornado-php.com
 * @license http://tornado-php.com/licencia/ MIT License
 * @version 2.0.0-beta
 */
abstract class Controller
{
    /**
     * Path de módulos HMVC
     * @var string
     */
    private $_path;

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
        $this->_path = $this->app->config('tornado_hmvc_module_path') . '/';
    }

    /**
     * Método que carga otro controlador
     * @param string $pController Módulo|Controlador
     */
    protected function loadController($pController)
    {
        $controller = explode('|', $pController);
        require $this->_path . $controller[0] . '/controller/' . $controller[1] . '.php';
    }

    /**
     * Método que carga una vista
     * @param string $pView   Módulo|Vista
     * @param array  $pParams Variables
     */
    protected function loadView($pView, $pParams = null)
    {
        if (is_array($pParams)) {
            extract($pParams);
            unset($pParams);
        }

        $view = explode('|', $pView);

        require $this->_path . $view[0] . '/view/' . $view[1] . '.tpl.php';
    }

    /**
     * Método que carga un modelo
     * @param string $pModel Módulo|Modelo
     */
    protected function loadModel($pModel)
    {
        $model = explode('|', $pModel);
        require $this->_path . $model[0] . '/model/' . $model[1] . '.php';
    }
}
