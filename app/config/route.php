<?php

$app = DMS\Core\Tornado::getInstance();

$app->route('HTTP', "/", "demo@demo@index");



$app->route('HTTP', '/article/list/all/:number', "demo@demo@index");


/*$app->route(array(
	"/"	=> function(){
		echo 'pepe';
	}
));*/

/*
 * Crear una clase Route
 * 
 * cambiar el método route para que pueda aceptar:
 * 
 * route('', 'url', function(){});
 * route('GET|POST /url', 'modulo@controlador@metodo');
 * 
 * route('GET', 'ruta', function(){});
 * route('GET', 'ruta', 'modulo@controlador@metodo');
 * 
 * route('GET|POST', 'ruta', function(){});
 * route('GET|POST', 'ruta', 'modulo@controlador@metodo');
 * 
 * o
 * route('ruta', function(){})->for('POST');
 * 
 */