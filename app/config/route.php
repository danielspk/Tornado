<?php

$app = \DMS\Tornado\Tornado::getInstance();

$app->route('/', 'demo|demo|index');

$app->route('/article/list/all/:number', 'demo|demo|index');

$app->route('/saludar/:alpha/:number', function ($pNombre = null, $pEdad = null) {
    echo 'Hola ' . $pNombre . ', Edad: ' . $pEdad;
});

$app->route('/saludador[/:alpha][/:number]', function ($pNombre = 'anonimo', $pEdad = '0') {
    echo 'Hola ' . $pNombre . ', Edad: ' . $pEdad;
});

$app->route('/felicitador/:*', function () {
    $params = func_get_args();
    echo 'Felicitaciones ' . (isset($params[0]) ? $params[0] : '');
});

$app->route('/bienvenida/@nombre:alpha/tornado/@edad:number', function () use ($app) {
    echo 'Hola ' . $app->param('nombre') . ', Edad: ' . $app->param('edad');
});