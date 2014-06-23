<?php

$app = DMS\Tornado\Tornado::getInstance();

$app->route('ALL', "/", "demo\demo\index");

$app->route('ALL', '/article/list/all/:number', "demo\demo\index");

$app->route('ALL', "/saludar/:alpha", function ($pNombre = null) {
    echo 'Hola ' . $pNombre;
});

$app->route('ALL', "/mostrar[/:alpha][/:number]", function ($pNombre = null, $pApellido = null) {
    echo 'Hola ' . $pNombre . ', ' . $pApellido;
});