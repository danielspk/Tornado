<?php

$app = \DMS\Tornado\Tornado::getInstance();

$app->hook('init', function () use ($app) {
    //print_r($app->getRouteModule());
});

$app->hook('end', function () {

});

$app->hook('404', function () {
    echo 'Error 404<br />Página no encontrada';
});

$app->hook('error', function () use ($app) {
    echo 'Error de Aplicación: <br />' . $app->error();
});
