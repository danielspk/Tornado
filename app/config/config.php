<?php

define('URLFRIENDLY', '/');

$app = \DMS\Tornado\Tornado::getInstance();

$app->config('tornado_url_hmvc_deny', false);
$app->config('tornado_environment_development', true);
