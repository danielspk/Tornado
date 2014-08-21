<?php

$app = \DMS\Tornado\Tornado::getInstance();

$app->hook('init', function () {

    ob_start(function ($pBuffer, $pPhase) {
        $buffer = mb_output_handler($pBuffer, $pPhase);
        $buffer = ob_gzhandler($pBuffer, $pPhase);

        return $buffer;
    });

});

$app->hook('end', function () {
    ob_end_flush();
});

$app->hook('404', function () {
    echo 'Error 404<br />Página no encontrada<br />';
    echo '<a href="./">Volver</a>';
});

$app->hook('error', function () use ($app) {
    echo 'Error de Aplicación: <br />' . $app->error();
    echo '<br /><a href="./">Volver</a>';
});
