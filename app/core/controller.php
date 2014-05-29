<?php
namespace DMS\Tornado;

/**
 * Clase base de controladores
 * 
 * @package TORNADO-CORE
 * @author Daniel Martín Spiridione <info@daniel-spiridione.com.ar>
 * @link https://github.com/danielspk/TORNADO
 * @license https://github.com/danielspk/TORNADO/blob/master/LICENSE MIT License
 * @version 0.9.0
 */
abstract class Controller
{
	
	/**
	 * Método que carga otro controlador
	 * @param string $pController Módulo/Controlador
	 */
	protected function loadController($pController)
	{
		$controller = explode('/', $pController);
		require 'app/modules/' . $controller[0] . '/controller/' . $controller[1] . '.php';	
	}
	
	/**
	 * Método que carga una vista
	 * @param string $pView Módulo/Vista
	 * @param array $pParams Variables
	 */
	protected function loadView($pView, $pParams)
	{
		if (is_array($pParams)) {
			extract($pParams);
			unset($pParams);
		}
		
		$view = explode('/', $pView);
		
		require 'app/modules/' . $view[0] . '/view/' . $view[1] . '.tpl.php';
	}
	
	/**
	 * Método que carga un modelo
	 * @param string $pModel Módulo/Modelo
	 */
	protected function loadModel($pModel)
	{
		$model = explode('/', $pModel);
		require 'app/modules/' . $model[0] . '/model/' . $model[1] . '_mod.php';
	}
	
}