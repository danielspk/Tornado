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
     * Path de módulos HMVC
     * @var string
     */
    private $path;

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
        $this->path = $this->app->config('tornado_hmvc_module_path') . '/';
    }

    /**
     * Método que carga otro controlador
     * @param string $pController Módulo|Controlador
     */
    protected function loadController($pController)
    {
        $controller = explode('|', $pController);
        $file = $this->path . $controller[0] . '/controller/' . $controller[1] . '.php';

        if (!file_exists($file))
            throw new \InvalidArgumentException('Error loading controller');

        require_once $file;
    }

    /**
     * Método que carga una vista
     * @param string $pView   Módulo|Vista
     * @param array  $pParams Variables
     */
    protected function loadView($pView, $pParams = null)
    {
        $view = explode('|', $pView);
        $file = $this->path . $view[0] . '/view/' . $view[1] . '.tpl.php';

        if (!file_exists($file))
            throw new \InvalidArgumentException('Error loading view');

        if (is_array($pParams)) {
            extract($pParams);
            unset($pParams);
        }

        require_once $file;
    }

    /**
     * Método que carga un modelo
     * @param string $pModel Módulo|Modelo
     */
    protected function loadModel($pModel)
    {
        $model = explode('|', $pModel);
        $file = $this->path . $model[0] . '/model/' . $model[1] . '.php';

        if (!file_exists($file))
            throw new \InvalidArgumentException('Error loading module');

        require_once $file;
    }
}
