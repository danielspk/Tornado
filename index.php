<?php
/**
 * TORNADO - CORE
 * 
 * Micro core para implementar el patrón HMVC y/o servicios REST.
 * 
 * @package TORNADO-CORE
 * @author Daniel Martín Spiridione <info@daniel-spiridione.com.ar>
 * @link https://github.com/danielspk/TORNADO
 * @license https://github.com/danielspk/TORNADO/blob/master/LICENSE MIT License
 * @version 0.9.0
 */

/*
 *             EDITE BAJO SU RIESGO - Lo puede comer el T-Rex
 *
 *                                                     ____
 *         ___                                      .-~. /_"-._
 *        `-._~-.                                  / /_ "~o\  :Y
 *            \  \                                / : \~x.  ` ')
 *             ]  Y                              /  |  Y< ~-.__j
 *            /   !                        _.--~T : l  l<  /.-~
 *           /   /                 ____.--~ .   ` l /~\ \<|Y
 *          /   /             .-~~"        /| .    ',-~\ \L|
 *         /   /             /     .^   \ Y~Y \.^>/l_   "--'
 *        /   Y           .-"(  .  l__  j_j l_/ /~_.-~    .
 *       Y    l          /    \  )    ~~~." / `/"~ / \.__/l_
 *       |     \     _.-"      ~-{__     l  :  l._Z~-.___.--~
 *       |      ~---~           /   ~~"---\_  ' __[>
 *       l  .                _.^   ___     _>-y~
 *        \  \     .      .-~   .-~   ~>--"  /
 *         \  ~---"            /     ./  _.-'
 *          "-.,_____.,_  _.--~\     _.-~
 *                      ~~     (   _}  
 *                              `. ~(
 *                                )  \
 *                               /,`--'~\--'
 * 
 *                         http://www.chris.com/
 */

// se cargan la librería del core
require 'app/core/tornado.php';

// se cargan las configuraciones
require 'app/config/config.php';
require 'app/config/route.php';
require 'app/config/hook.php';

// se inicia el tornado
DMS\Tornado\Tornado::getInstance()->run();