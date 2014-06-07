<?php
/**
 * TORNADO - CORE PHP
 * 
 * Micro core para implementar el patrón HMVC y/o servicios REST.
 * 
 * @package TORNADO-CORE
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link https://github.com/danielspk/TORNADO
 * @license https://github.com/danielspk/TORNADO/blob/master/LICENSE MIT License
 * @version 0.9.0
 */

/*
 *             EDITE BAJO SU RIESGO - El tornado lo puedo arrasar
 * 
 * 
 *                         . '@(@@@@@@@)@. (@@) `  .   '
 *               .  @@'((@@@@@@@@@@@)@@@@@)@@@@@@@)@ 
 *               @@(@@@@@@@@@@))@@@@@@@@@@@@@@@@)@@` .
 *            @.((@@@@@@@)(@@@@@@@@@@@@@@))@\@@@@@@@@@)@@@  .
 *           (@@@@@@@@@@@@@@@@@@)@@@@@@@@@@@\\@@)@@@@@@@@)
 *          (@@@@@@@@)@@@@@@@@@@@@@(@@@@@@@@//@@@@@@@@@) ` 
 *           .@(@@@@)##&&&&&(@@@@@@@@)::_=(@\\@@@@)@@ .   .'
 *             @@`(@@)###&&&&&!!;;;;;;::-_=@@\\@)@`@.
 *             `   @@(@###&&&&!!;;;;;::-=_=@.@\\@@     '
 *                `  @.#####&&&!!;;;::=-_= .@  \\
 *                      ####&&&!!;;::=_-        `
 *                       ###&&!!;;:-_=
 *                        ##&&!;::_=
 *                       ##&&!;:=
 *                      ##&&!:-
 *                     #&!;:-
 *                    #&!;=
 *                    #&!-
 *                     #&=
 *             PHP      #&-
 *                      \\#/'
 * ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
 * Fuente: http://www.chris.com/
 */

// se cargan la librería del core
require 'app/core/tornado.php';

// se cargan las configuraciones
require 'app/config/config.php';
require 'app/config/route.php';
require 'app/config/hook.php';

// se inicia el tornado
DMS\Tornado\Tornado::getInstance()->run();