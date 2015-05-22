TORNADO
============

[![Build Status](https://travis-ci.org/danielspk/Tornado.svg)](https://travis-ci.org/danielspk/Tornado)
[![Latest Stable Version](https://poser.pugx.org/danielspk/Tornado/v/stable.svg)](https://packagist.org/packages/danielspk/Tornado)
[![Total Downloads](https://poser.pugx.org/danielspk/Tornado/downloads.svg)](https://packagist.org/packages/danielspk/Tornado)
[![License](https://poser.pugx.org/danielspk/Tornado/license.svg)](https://packagist.org/packages/danielspk/Tornado)

![ScreenShot](http://tornado-php.com/wp-content/uploads/2014/08/tornado-php.png)

TORNADO es un reducido marco de trabajo para PHP que permite implementar el 
patrón HMVC y/o servicios RESTfull

Puede obtener más información en su web http://tornado-php.com

### Filosofia:

TORNADO no intenta ser un framework PHP full-stack. Contrariamente intenta ser  
un núcleo de trabajo muy reducido para implementar patrones de arquitectura HMVC 
y/o servicios REST, con la menor parametrización y utilización de código 
posible, apoyado en un core que organice su proyecto junto a un sistema de 
configuración y gestión de errores simple.

TORNADO no incluye librerías de soporte para tareas comunes como acceso a base 
de datos, gestión de plantillas, envío de mais, etc.
Utilice Composer para incluir paquetes de terceros de acuerdo a las necesidades 
particulares del proyecto a desarrollar.

Puede obtener más información de la filosofía del core mirando la wiki del 
proyecto.

### Inspiración:

TORNADO se inspiro en varios microframeworks PHP, entre ellos cabe mencionar:

- Toro - http://toroweb.org/
- Flight - http://flightphp.com/
- Shield - https://github.com/enygma/shieldframework
- Slim - http://www.slimframework.com/
- AltoRouter - http://altorouter.com/

### Metas:

TORNADO se desarrollo tratando de respetar las siguiente metas:

- ser rápido
- fácil de entender _(tanto su API como su construcción interna)_
- tener la menor cantidad de métodos posibles dentro de su API
- permitir el uso de ganchos para extender el mismo
- incluir librerías/paquetes de terceros con suma facilidad
- tener la menor cantidad de líneas de código posible
- ser un core de trabajo _(NUNCA un framework)_

## Características:

- Enrutamientos para utilizar módulos HMVC y/o servicios REST (apoyado en URL 
amigables)
- Configuración general de la aplicación
- Ganchos para extender las características del core
- Captura de errores y excepciones
- Inyección de dependencias

### Codificación:

TORNADO apoya la iniciativa del PHP Framework Interop Group e implementa los 
estándares PSR-2 y PSR-4.

Puede obtener más información en http://www.php-fig.org/

## Instalación:

La instalación recomendada requiere el uso de Composer. 

1. Instale composer ( puede obtener ayuda en https://getcomposer.org/download/ )

2. Cree un archivo composer.json con los paquetes a instalar

```
{
    "require": {
        "danielspk/tornado" : "2.*"
    }
}
```

3. Inicie la consola de comando y ejecute el siguiente comando

```
composer install
```

## Manual de uso:

La versión actual difiere totalmente de la versión inicial 1.0.0

Si va a actualizar su aplicación lea en detalle el archivo de cambios CHANGELOG.md

#### Uso básico:
Ejemplo de uso básico (con dos tipos de enrutamientos)

```php
<?php

    // incluir el autoload
    require 'vendor/autoload.php';
    
    // obtener instancia del core
    $app = \DMS\Tornado\Tornado::getInstance();
    
    // enrutamiento a módulo desde raíz
    $app->route('/', 'demo|demo|index');
    
    // enrutamiento a función anónima
    $app->route(array(
        '/saludar/:string'	=> function($pNombre = null){
            echo 'Hola ' . $pNombre;
        }
    ));

    // ejecutar la aplicación
    $app->run();
    
```

#### API:

##### Obtener Instancia del core:

```php
    $app = \DMS\Tornado\Tornado::getInstance();
```

##### Ejecutar el core:

```php
	// con una instancia del core en una variable
    $app = \DMS\Tornado\Tornado::getInstance();
    $app->run();

    // sin ninguna instancia del core en una variable
    \DMS\Tornado\Tornado::getInstance()->run();
```

##### Setear configuraciones:

```php
    $app = \DMS\Tornado\Tornado::getInstance();

    // configuración simple
    $app->config('nombre', 'valor del nombre');
    $app->config('nombres', array('nombre1'=>'valor1', 'nombre2'=>'valor2'));
    
    // configuración múltiple
    $app->config([
        'clave1' => 'valor uno',
        'clave2' => 'valor dos'
    ]);
```

##### Leer configuraciones:

```php
    $app = \DMS\Tornado\Tornado::getInstance();

    // configuración simple
    echo $app->config('nombre');

    // configuración array
    $nombres = $app->config('nombres');
    echo $nombres[0]['nombre1'];
    echo $nombres[1]['nombre2'];
```

##### Variables de configuración propias de Tornado:

Tornado permite configurar el ambiente de trabajo de la aplicación.
De esta forma se puede cambiar el comportamiento interno del core:

```php

    // configura la aplicación para un ambiente de desarrollo
    // - errores visibles
    // - parse de anotaciones en módulos HMVC para generar enrutamientos automáticos
    $app->config('tornado_environment_development', true);

```

Otras configuraciones:

```php

    // - indica si se van a utilizar módulos hmvc
    $app->config('tornado_hmvc_use', true);

    // - ruta donde se alojarán los módulos hmvc
    // (relativa a donde se inicie Tornado)
    $app->config('tornado_hmvc_module_path', true);
        
    // - ruta donde se serilizaran las rutas de los módulos hmvc
    // (relativa a donde se inicie Tornado)
    $app->config('tornado_hmvc_serialize_path', true);
        
```

##### Uso de Hooks:
Existen 6 tipos de hooks:
- init: antes de parsear la url en busca de una ruta coincidente
- before: antes de ejecutar la ruta coincidente
- after: despues de ejecutar la ruta coincidente
- end: al finalizar la ejecución de la petición
- 404: al no encontrarse una ruta coincidente con la url
- error: al atraparse un error o excepción en aplicación

```php

    $app = \DMS\Tornado\Tornado::getInstance();

    // utilizando una clase / método / parámetros
    $app->hook('error', array('ErrorUser', 'display', array()));

    // utilizando una función anónima
    $app->hook('404', function(){
        echo '404';
    });
    
```

También es posible crear ganchos personalizados. Ejemplo usando una clase de usuario:

```php

    class Saludador
    {
        public function persona($nombre, $apellido)
        {
            echo 'Hola ' . $nombre . ', ' . $apellido;
        }
    }

    $app->hook('saludar', array('Saludador', 'persona', array('Tornado', 'PHP')));
    
```

La forma de ejecutar un gancho por código es la siguiente:

```php

    $app = \DMS\Tornado\Tornado::getInstance();

    $app->hook('fueraDeLinea');
    
```

Pueden crearse n cantidad de hooks con un mismo nombre. Los mismos se ejecutarán 
secuencialmente en el orden en que fueron definidos. Puede, opcionalmente, alterar 
este orden indicando explicitamente el orden deseado:

```php

    $app = \DMS\Tornado\Tornado::getInstance();

    $app->hook('before', function(){
        echo 'Declarado primero - ejecutado despues';
    }, 1);
    
    $app->hook('before', function(){
        echo 'Declarado despues - ejecutado primero';
    }, 0);
    
```

Si declara más de un hook con el mismo nombre puede impedir que se ejecuten los hooks 
subsiguientes haciendo que el hook devuelva false en su ejecución.

A excepción de los hook init puede consultar que ruta se va o se esta ejecutandose de 
la siguiente forma:

```php

    $app = \DMS\Tornado\Tornado::getInstance();

    $app->hook('before', function() use ($app){
        $ruta = $app->getRouteMatch()
    });
    
```

Esto devolverá un array con la siguiente información:

- Método de la petición (GET, POST, etc)
- Ruta
- Callback
- Parámetros

##### Hooks y flujo de ejecución:

La secuencia de ejecución del core es la siguiente:
- se ejecutan los hooks init
- se parsea la url en busca de la ruta coincidente
- - si no hay coincidencias se ejecuta:
- - - hooks 404
- - - hooks end
- - - se finaliza la ejecución
- se ejecutan los hooks before
- - si alguno devuelve false se ejecuta:
- - - hooks end
- - - se finaliza la ejecución
- se ejecuta la ruta coincidente
- se ejecutan los hooks after
- se ejecutan los hooks end

##### Definir Enrutamientos:
Los enrutamientos pueden ser:
- (vacio) - cualquier tipo de petición
- GET - RESTfull por método GET
- POST - RESTfull por método POST
- PUT - RESTfull por método PUT
- DELETE - RESTfull por método DELETE

En caso de que el servidor no soporte los métodos PUT y DELETE se pueden simular 
los mismos enviando una petición POST con una variable "REST_METHOD" cuyo valor 
sea PUT o DELETE

Existen cuatro tipos de parámetros para enrutar una URL:
- :string - sólo acepta letras
- :number - sólo acepta números
- :alpha - acepta números y letras
- :* - acepta cualquier cantidad y tipo de parámetros (sólo puede incluirse uno solo y al final)

En caso de incluir parámetros opcionales la sintaxis es la siguiente:
- [/:string]
- [/:number]
- [/:alpha]

```php

    $app = \DMS\Tornado\Tornado::getInstance();

    // utilizando un módulo y cualquier tipo de petición
    $app->route('/', 'demo|demo|index');

    // utilizando una función anónima y cualquier tipo de petición
    $app->route('/saludar/:alpha', function($pNombre = null) {
        echo 'Hola ' . $pNombre;
    });

    // utilizando parámetros opcionales y cualquier tipo de petición
    $app->route('/mostrar[/:alpha][/:number]', function ($pNombre = null, $pEdad = null) {
        echo 'Hola ' . $pNombre . ', ' . $pEdad;
    });

    // utilizando un comodín (n cantidad de parámetros) y cualquier tipo de petición
    $app->route('/felicitador/:*', function () {
        $params = func_get_args();
        echo 'Felicitaciones ' . (isset($params[0]) ? $params[0] : '');
    });

    // utilizando un módulo y petición POST
    $app->route('POST /', 'demo|demo|guardar');

    // utilizando un módulo y petición GET o POST
    $app->route('GET|POST /', 'demo|demo|listar');
    
```

También es posible definir parámetros con nombre. En dicho caso puede omitirse 
el uso de parámetros de entrada en las funciones anónimas o métodos de los 
módulos HMVC. Ejemplo:

```php

    $app->route('/bienvenida/@nombre:alpha/tornado/@edad:number', function () use ($app) {
        echo 'Hola ' . $app->param('nombre') . ', Edad: ' . $app->param('edad');
    });
    
```

Puede agregar tipos de parámetros auxiliares de la siguiente forma:

```php

    $app = \DMS\Tornado\Tornado::getInstance();

    $app->addTypeParam(':custom', '([123]+)');
    
    $app->route('/personalizado/:custom', function ($pCustom = null) {
        echo 'Parametro personalizado ' . $pCustom;
    });
    
```

Nota: El único enrutamiento obligatorio es el del nodo raíz ya que indica cuál será el 
callback a ejecutar por defecto al ingresar a la aplicación.

##### Delegaciones:
Es posible delegar la acción de un módulo/ruta hacia otro sin necesidad de realizar 
una redirección por http. Esta delegación invoca al otro módulo/ruta dentro del mismo 
request original. Ejemplo:

```php

    // a módulo sin parámetros
    $app->forwardModule('modulo|clase|metodo');

    // a módulo con parámetros
    $app->forwardModule('modulo|clase|metodo', array('param1', 'param2'));

    // a url (parámetros incluidos en la url)
    $app->forwardUrl('/otra/ruta/1234');
    
```

Si se encuentra instalado FPM en el Servidor puede devolver el resultado al cliente y 
continuar con la ejecución de la petición actual en segundo plano de la siguiente forma:

```php

    $app->finishRequest();
 
```

##### Anotaciones:
Algunas acciones pueden ser establecidas mediante anotaciones DocBlocks.

###### Enrutamientos:
En los controladores de los módulos HMVC puede utilizar el tag @T_ROUTE para 
setear un enrutamiento. Esto generará un archivo de configuración denominado 
"route_serialize.php".

Siempre que la aplicación se encuentre en modo de desarrollo (variable de 
configuración "tornado_environment_development" en true) se recorrerán los 
métodos de los controladores para actualizar este archivo de configuración.

Ejemplo:

```php

    class Demo extends \DMS\Tornado\Controller
    {
        /**
         * Ejemplo de enrutamientos mediante anotaciones
         * @T_ROUTE /demo/anotacion
         * @T_ROUTE GET|POST /demo/otra/anotacion
         */
        public function index()
        {
            echo 'Hola Mundo Tornado';
        }
    }

```

##### Vistas
Puede incluir archivos de vistas/templates dentro de una ruta manejada por 
clousures de la siguiente forma:

```php
    $app = \DMS\Tornado\Tornado::getInstance();

    $app->render('ruta/archivo.php');  // vista sin parámetros
    $app->render('ruta/archivo.php', array('nombre'=>'valor')); // vista con parámetros
```

Los parámetros pasados a la vista/template se manejan de la misma forma que los 
parámetros pasados a una vista de un módulo HMVC.

##### Gestión de errores y excepciones:
El manejo de errores y excepciones viene habilitado por defecto. Puede alterar 
su comportamiento de la siguiente forma:

```php

    $app = \DMS\Tornado\Tornado::getInstance();

    $app->error(true);  // habilita el manejador
    $app->error(false); // deshabilita el manejador
    
```

Puede acceder a la última excepción lanzada de la siguiente forma:

```php

    $app = \DMS\Tornado\Tornado::getInstance();

    $exc = $app->error();
    
```

##### Inyección de Dependencias:
Es posible extender el core mediante la inyección de nuevas clases.
La forma de registrar una nueva dependencia es:

```php

    $app->register('fecha', function($fecha = '2014-12-31'){
        return new \DateTime($fecha);
    });
    
```

Este registro creará un dependencia para la clase 'DateTime' denominada 'fecha'.
Podrá hacer uso de la misma de la siguiente forma:

```php

    $app = \DMS\Tornado\Tornado::getInstance();

    echo $app->container('fecha')->format('d/m/Y') . '<br />';
    
```

Por defecto todas las dependencias inyectadas crean una nueva instancia de la clase.
Puede registrar el servicio como Singleton seteando el tercer parámetro opcional en true:

```php

    $app->register('fecha', function(){
        return new \DateTime('2014-12-31');
    }, true);
    
```

Si las dependencias requieren parámetros en sus constructores puede definir los mismos 
de la siguiente forma:

```php

    $app->register('fecha.config', '2014-12-31');
    
    $app->register('fecha', function(\DMS\Tornado\Service $c){
        return new \DateTime($c->get('fecha.config'));
    });
    
```

##### Organización de proyecto:

Existe un proyecto que dispone de un esqueleto para una aplicación base.
Puede descargar el mismo desde https://github.com/danielspk/TornadoSkeletonApplication

#### Módulos:

Tornado PHP permite utilizar módulos HMVC de forma conjunta con las funciones anónimas.

Si utiliza Composer, se recomienda registrar la ubicación de los módulos en el autoload. Ejemplo:

```
    "autoload": {
        "psr-4": {
            "App\\Modules\\": "app/modules/"
        }
    },
```

##### Controladores:
Todos los controladores deben extender de \DMS\Tornado\Controller y deben 
definir un namespace que respete la especificación PSR-4. Ejemplo: 

Asumiendo que los módulos HMVC se encuentran en App\Modules\[Modulo HMVC]\Controller

```php

    namespace App\Modules\Demo\Controller;

    use \DMS\Tornado\Controller;
    
    class Demo extends Controller {
        public function index($param = null){
            echo ' Hola ' . $param . '<br>';
        }
    }
    
```

Los Controladores poseen una instancia de tornado PHP como propiedad propia. Puede acceder a la misma de la siguiente forma:

```php

    // permite acceder a una instancia de Tornado
    $app = $this->app;
    
```

##### Modelos:
Todos los controladores deben definir un namespace que respete la siguiente 
jerarquía: App\Modules\[Modulo HMVC]\Model

```php

    namespace App\Modules\Demo\Model;

    class Demo {
        public function getDemos($param = null){
            return true;
        }
    }
    
```

##### Vistas:
Dado que el controlador posee una instancia de Tornado es posible usar el método render() para invocar a una vista.

## Resumen de Métodos:

**DMS\Tornado\Tornado**

| Método | Detalle |
| ------ | ------- |
| getInstance() | Devuelve la instancia de Tornado (si no existe la crea) |
| run() | Arranca el core |
| config(string) | Devuelve el valor de la variable de configuración |
| config(array) | Setea un array de configuración |
| config(string, mixed) | Setea el valor en la variable de configuración |
| error() | Devuelve la última excepción atrapada |
| error(bool) | Habilita/deshabilita el manejador interno de errores y excepciones |
| hook(string) | Ejecuta el gancho indicado |
| hook(string mixed) | Registra un gancho y su callback |
| route(string, mixed) | Registra un enrutamiento y su callback |
| addTypeParam(string, string) | Registra un nuevo tipo de parámetro |
| register(string, callable, [bool]) | Registra una clase/servicio para extender la aplicación |
| container(string) | Devuelve un servicio o parámetro |
| render(string) | Incluye una vista/template |
| render(string, array) | Incluye una vista/template junto a un array de variables |
| param(string) | Devuelve el valor de un parámetro del enrutamiento |
| getRouteMatch() | Devuelve la ruta que se esta procesando |
| forwardModule(string) | Delega la acción hacia otro módulo |
| forwardModule(string, array) | Delega la acción hacia otro módulo |
| forwardUrl(string) | Delega la acción hacia otra ruta |
| finishRequest() | Devuelve el request al cliente y continua la ejecución del script actual |

**DMS\Tornado\Service**

| Método | Detalle |
| ------ | ------- |
| get | Devuelve un servicio o parámetro |

**DMS\Tornado\Controller**

| Atributo | Detalle |
| ------ | ------- |
| app | Instancia de Tornado |

## Licencia:

El proyecto se distribuye bajo la licencia MIT.

### Tests unitarios:

Para ejecutar los test es necesario PHPUnit. 
Sitúese en la carpeta raíz de Tornado y ejecute la siguiente instrucción por línea de comendo: 

```
    phpunit
```

Ante errores o sugerencias escriba a la dirección de email de contacto.

## Sugerencias y colaboración:

Email: info@daniel.spiridione.com.ar
