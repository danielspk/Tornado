<?php

define('URLFRIENDLY', '/');

$app = DMS\Tornado\Tornado::getInstance();

$app->config('nombre', 'valor');
$app->config('nombres', array('nombre1'=>'valor1', 'nombre2'=>'valor2'));

$app->autoload(true);
$app->autoload('Twing\Twing', array('twing/lib/src', 'twing/lib/test'));

$config = $app->config('nombres');
