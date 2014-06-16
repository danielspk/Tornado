<?php

$app = DMS\Tornado\Tornado::getInstance();

$app->config('nombre', 'valor');
$app->config('nombres', array('nombre1'=>'valor1', 'nombre2'=>'valor2'));

$app->autoload()->addNamespace('Twing\Twing', array('twing/lib/src'));

$config = $app->config('nombres');
