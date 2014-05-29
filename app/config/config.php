<?php
/*
 * Ejemplo:
 * 
 * $config = $app->config('db');
 * echo $config . '<br />';
 * 
 * $config = $app->config('db2');
 * echo $config['clave'];
 */

$app = DMS\Tornado\Tornado::getInstance();

$app->config('db', 'nombre de base');
$app->config('db2', array('clave'=>'valor'));

$app->autoload()->addNamespace('Twing\Twing', array('twing/lib/src'));


$config = $app->config('db');