<?php

$app = DMS\Tornado\Tornado::getInstance();

$app->route('HTTP', "/", "demo@demo@index");

$app->route('HTTP', '/article/list/all/:number', "demo@demo@index");