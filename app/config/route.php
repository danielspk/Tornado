<?php

$app = DMS\Tornado\Tornado::getInstance();

$app->route('/', 'demo|demo|index');

$app->route('/article/list/all/:number', 'demo|demo|index');

$app->route('/saludar/:alpha', function ($pNombre = null) {
    echo 'Hola ' . $pNombre;
});

$app->route('/saludar[/:alpha][/:number]', function ($pNombre = null, $pEdad = null) {
    echo 'Hola ' . $pNombre . ', Edad: ' . $pEdad;
});
