<?php
/**
 * TORNADO - CORE PHP
 *
 * Micro core para implementar el patrón HMVC y/o servicios REST.
 *
 * @package TORNADO-CORE
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link http://tornado-php.com
 * @license http://tornado-php.com/licencia/ MIT License
 * @version 1.0.0
 */

/*
 * ATENCIÓN: Edite bajo su riego, el tornado lo puedo arrasar.
 */

/*
 *
 *                                                     ,''
 *                                                     @@@
 *                                                     @@:
 *                                                     @@
 *                                          @@@@@@@+   @@@@@@@   @@@@@@@`  .@@#:`
 *                                          @@@@@@@@. ;@@@@@@@'  @@@@@@@@    :@@@@@@#,
 *                                          @@`   @@@ @@@   @@' .@@    @@`       ,@@@@@@@,
 *                           .:+@@@@@@      @@    @@@ @@:   @@, +@@    @@`          ,@@@@@@@:
 *                      ;#@@@@@@@'`        :@@    @@+ @@    @@  @@#    @@              '@@@@@@@.
 *                  :@@@@@@@'.             @@@    @@  @@   `@@  @@,   #@@                ,@@@@@@@:
 *              `+@@@@@@@,                 @@'   @@@ ;@@   +@@  @@   ,@@:                  ,@@@@@@@:
 *            ;@@@@@@@:                    @@@@@@@@  @@@   @@# .@@@@@@@@                     @@@@@@@@`
 *          #@@@@@@@.                      @@@@@@'   @@:   @@, +@@@@@@.                       :@@@@@@@+
 *        @@@@@@@@.                       ,@@                  @@#        @@@'`                `@@@@@@@@
 *      '@@@@@@@'                         @@@                  @@.         .@@@@@'`             ,@@@@@@@@
 *     @@@@@@@@.               `,;##'.    @@'                  @@            ;@@@@@@+            @@@@@@@@;
 *    @@@@@@@@`            `+@@@@@@#                                           '@@@@@@#          ,@@@@@@@@
 *   @@@@@@@@+           +@@@@@@@:                                               +@@@@@@;        `@@@@@@@@,
 *   @@@@@@@@          #@@@@@@@.                                                   @@@@@@@       `@@@@@@@@#
 *  '@@@@@@@@        ,@@@@@@@`                                                      ;@@@@@@      '@@@@@@@@#
 *  #@@@@@@@@       '@@@@@@,                                                         '@@@@@:     @@@@@@@@@.
 *  ,@@@@@@@@      ,@@@@@@               :##                           +@@#`          @@@@@@    +@@@@@@@@@
 *   @@@@@@@@@     @@@@@@            '@@@#                              `@@@@         @@@@@@.  :@@@@@@@@@;
 *   ,@@@@@@@@;   ;@@@@@'         `@@@@`                                  @@@@`       @@@@@@# :@@@@@@@@@@
 *    ;@@@@@@@@#  @@@@@@.        @@@@                                      @@@@      ,@@@@@@@+@@@@@@@@@@
 *     .@@@@@@@@@.#@@@@@@       @@@@             `,''@@@@#;;,.             #@@@:    `@@@@@@@@@@@@@@@@@;
 *       @@@@@@@@@@@@@@@@+     `@@@@        `+@@@@@@@@@@@@@@@@@@@;         +@@@@   .@@@@@@@@@@@@@@@@@ `
 *        `@@@@@@@@@@@@@@@@    `@@@@       #@@@@@@@@@@@@@@@@@@@@@@@        @@@@@  #@@@@@@@@@@@@@@@@. #.
 *           ;@@@@@@@@@@@@@@;   @@@@        @@@@@@@@@@@@@@@@@@@@@@@       @@@@@++@@@@@@@@@@@@@@@@` ,@@
 *              ;@@@@@@@@@@@@@#.;@@@@.       @@@@@@@@@@@@@@@@@@@@@`     `@@@@@@@@@@@@@@@@@@@@@:  `@@@'
 *                 @@@@@@@@@@@@@@@@@@@@`       ;@@@@@@@@@@@@@@;`      `@@@@@@@@@@@@@@@@@@@@,   `@@@@@
 *                 '@@@@#@@@@@@@@@@@@@@@@#.                        .#@@@@@@@@@@@@@@@@@@+.    ,@@@@@@
 *                  @@@@@+ `+@@@@@@@@@@@@@@@@@#'``           .;+@@@@@@@@@@@@@@@@@@@+`      +@@@@@@@
 *                   +@@@@@;    .:+@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@:.        ;@@@@@@@@#
 *                    ,@@@@@@'          `::++#@@@@@@@@@@@@@@@@@@@@@#++::`           ,#@@@@@@@@@@
 *                      @@@@@@@@.                                              `'@@@@@@@@@@@@#
 *                       `@@@@@@@@@.         ```                       `,;+@@@@@@@@@@@@@@@'`
 *                       #; #@@@@@@@@@.  '#@@@@@@@@@@@+;.,.:'++@@@@@@@@@@@@@@@@@@@@@@#;`         .@+
 *                        @@@,,@@@@@@@@@@;     `.:#@@@@@@@@@@@@@@@@@@@@@@@@@@@@#;.`            +@@@
 *                         @@@@; :@@@@@@@@@@@;`             `,,,.,,,.,,.`                  `+@@@@@,
 *                          @@@@@+  ,@@@@@@@@@@@@#,                                     '@@@@@@@@+
 *                           @@@@@@'   .#@@@@@@@@@@@@@@+:`                        .:#@@@@@@@@@@@,
 *                            +@@@@@@#     .+@@@@@@@@@@@@@@@@@@@##':,,:,,;'#@@@@@@@@@@@@@@@@@@+
 *                             `@@@@@@@@:      .'@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@,
 *                               #@@@@@@@@@;        .:@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@#.
 *                                 #@@@@@@@@@@#.          `,:+#@@@@@@@@@@@@@@@@+:.           .@
 *                                   #@@@@@@@@@@@@'`                                       '@@+
 *                                     :@@@@@@@@@@@@@@+.                                ,@@@@@
 *                                        '@@@@@@@@@@@@@@@@#:`                     `,#@@@@@@@'
 *                                           `#@@@@@@@@@@@@@@@@@@@@+':;:,,,::;+#@@@@@@@@@@@@'
 *                                               `:#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
 *                                       `+',`          .+#@@@@@@@@@@@@@@@@@@@@@@@@@@@@@;
 *                                        `@@@@@'`                  `..````......`.`
 *                                          @@@@@@@@.
 *                                           :@@@@@@@@@;
 *                                             +@@@@@@@@@@@;,`         .,,;;+@@@
 *                                               ;@@@@@@@@@@@@@@@@@@@@@@@@@@@@,
 *                                          @#     `#@@@@@@@@@@@@@@@@@@@@@@@'
 *                                          '@@@.      :@@@@@@@@@@@@@@@@@#.
 *                                           @@@@@@.        `;'####+',
 *                                            +@@@@@@@@',
 *                                             .@@@@@@@@@@@@@@@@@@#######`
 *                                               #@@@@@@@@@@@@@@@@@@@@@@
 *                                          @.     @@@@@@@@@@@@@@@@@@@:
 *                                          +@@.     ,#@@@@@@@@@@@@+`
 *                                           @@@@#,      ``.,,.``
 *                                            @@@@@@@@@+:';:,,,.`
 *                                             @@@@@@@@@@@@@@@@@@
 *                                              ;@@@@@@@@@@@@@@@
 *                                                '@@@@@@@@@@@`
 *                                                   ,+@##'`
 *                                         @@'`
 *                                         '@@@@@@#+;;'#
 *                                          @@@@@@@@@@@:
 *                                           @@@@@@@@@,
 *                                           :@@@@@@@,
 *                                            :@@@@@,
 *                                             :@@@#
 *                                              `@@
 *                                                @
 *
 * Generado por: http://picascii.com/
 */

// se carga la librería del core
require 'app/core/tornado.php';

// se cargan las configuraciones
require 'app/config/config.php';
require 'app/config/route.php';
require 'app/config/hook.php';

// se inicia el tornado
\DMS\Tornado\Tornado::getInstance()->run();
