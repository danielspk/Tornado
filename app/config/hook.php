<?php
/**
 * Eventos nativos disponibles:
 * 
 * - init		: previo a ejecutar al módulo
 * - end		: al terminar de ejecutar el módulo
 * - error		: en caso de producirse un error
 * - 404		: en caso de no poder encontrar un módulo para la URL
 */
/*
 * Ejemplo:
 * 
 * $app->hook('end', array('pepe', 'pepe2'));
 * 
 * class pepe{
 * 	public static function pepe2(){
 *   echo 'pepe12';
 *  }
 * }
 * 
 */

$app = DMS\Tornado\Tornado::getInstance();

$app->hook('offline', function(){
	echo 'Fuera de línea';
	exit();
});

$app->hook('init', function(){
	
	//DMS\Core\Tornado::getInstance()->hook()->call('offline');
	
	/*ob_start(function($pBuffer, $pPhase){
		$buffer = mb_output_handler($pBuffer, $pPhase);
		$buffer = ob_gzhandler($pBuffer, $pPhase);
		return $buffer;
	});*/
	
});

$app->hook('end', function(){
	ob_end_flush();
});
	
$app->hook('404', function(){
	echo '404';
});

$app->hook('error', function() use ($app){
	echo 'error: <br />' . $app->error()->getCurrentException();
});