TORNADO
============

![ScreenShot](http://tornado-php.com/wp-content/uploads/2014/08/tornado-php.png)

TORNADO es un reducido marco de trabajo para PHP que permite implementar el 
patrón HMVC y/o servicios RESTfull

Puede obtener más información en su web http://tornado-php.com

### Filosofia:

TORNADO no intenta ser un gran framework full-stack. Contrariamente intenta ser  
un núcleo de trabajo muy reducido para implementar patrones de arquitectura HMVC 
y/o servicios REST, con la menor parametrización y utilización de código 
posible, apoyado en un core que organice el proyecto y un sistema de 
configuración y gestión de errores simple.

TORNADO no incluye librerías de soporte para tareas comunes como acceso a base 
de datos, gestión de plantillas, envío de mais, etc.
Puede utilizar Composer para incluir librerías de terceros y de acuerdo a las 
necesidades particulares del proyecto a desarrollar.

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
- ser fácil de entender _(tanto su API como su construcción interna)_
- tener la menor cantidad de métodos posibles dentro de su API
- permitir definir ganchos para que los programadores puedan extender el mismo
- permitir incluir librerías de terceros con suma facilidad
- ser ligero respecto a la cantidad de líneas de código a mantener
- ser un core de trabajo _(NUNCA un framework)_

## Características:

- Enrutamientos para utilizar módulos HMVC y/o servicios REST (apoyado en URL 
amigables)
- Configuración de la aplicación
- Ganchos para extender las características del core
- Captura de errores y excepciones
- Inyección de dependencias
- Auto carga de librerías de terceros

### Codificación:

TORNADO apoya la iniciativa del PHP Framework Interop Group e implementa los 
estándares PSR-1, PSR-2 y PSR-4.

## Instalación:

El core no requiere instalación alguna. Basta con descargar el proyecto y 
copiarlo en alguna ubicación del servidor web.
También puede utilizar "composer" para su instalación - Ejemplo:

1. Instale composer ( puede obtener ayuda en https://getcomposer.org/download/ )

2. Inicie la consola de comando y ejecute el siguiente comando

```
composer create-project danielspk/tornado ruta/al/proyecto
```

3. En caso de querer utilizar URL amigables edite el archivo .htaccess y 
modifique las líneas 4 y 5 de acuerdo a la ubicación del proyecto dentro del 
servidor y las restricciones que quiera aplicar a los redireccionamientos.

## Manual de uso:

#### Uso básico:
Ejemplo de uso básico (con dos tipos de enrutamientos)

```php
<?php

    // incluir el core
    require 'app/core/tornado.php';
    
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

    $app->config('nombre', 'valor del nombre');
    $app->config('nombres', array('nombre1'=>'valor1', 'nombre2'=>'valor2'));
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
    // - parse de anotaciones de métodos HMVC para generar enrutamientos automáticos
    $app->config('tornado_environment_development', true);

```

##### Habilitar/deshabilitar autoload y setear namespaces:

```php
    $app = \DMS\Tornado\Tornado::getInstance();

    $app->autoload(true); // false para deshabilitar
    $app->autoload('Twing\Twing', array('twing/lib/src', 'twing/lib/test'));
```

##### Uso de Hooks:
Existen 4 tipos de hooks:
- init: antes de parsear la url
- before: antes de ejecutar la ruta
- after: despues de ejecutar la ruta
- end: al finalizar la ejecución
- 404: al no encontrarse una ruta coincidente a la url
- error: al atraparse un error de aplicación

```php
    $app = \DMS\Tornado\Tornado::getInstance();

    // utilizando una clase / método / parámetros
    $app->hook('error', array('ErrorUser', 'display', array()));

    // utilizando una función anónima
    $app->hook('404', function(){
        echo '404';
    });
```

También es posible crear ganchos personalizados. Ejemplo usando una clase de 
usuario (se asume que ya debe estar incluida o incluida en el autoload):

```php
class Saludador
{
    public function persona($nombre, $apellido)
    {
        echo 'Hola ' . $nombre . ', ' . $apellido;
    }
}

$app->hook('saludar', array('Saludador', 'persona', array('Tornado', 'Core')));
```

La forma de ejecutar un gancho por código es la siguiente:

```php
    $app = \DMS\Tornado\Tornado::getInstance();

    $app->hook('fueraDeLinea');
```

Pueden crearse n cantidad de hooks con un mismo nombre. Los mismos se ejecutarán 
secuencialmente en el orden en que fueron definidos. Puede alterar este orden indicando
explicitamente el orden deseado:

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
haciendo que el hook devuelva false en su ejecución.

A excepción de los hook init puede consultar que ruta se va o esta ejecutandose:

```php
    $app = \DMS\Tornado\Tornado::getInstance();

    $app->hook('before', function() use ($app){
        $ruta = $app->getRouteMatch()
    });
```

##### Hooks y flujo de ejecución:

La secuencia de ejecución del core es la siguiente:
- se ejecutan los hooks init
- se parsea la url en busca de la ruta a ejecutar
- - si no hay coincidencias se ejecuta:
- - - hooks 404
- - - hooks end
- - - se finaliza
- se ejecutan los hooks before
- - si alguno devuelve false se ejecuta:
- - - hooks end
- - - se finaliza
- se ejecuta la ruta
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

    // a url (parámetros incluidos en url)
    $app->forwardUrl('/otra/ruta/1234');
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
        return new DateTime($fecha);
    });
```

Este registro creará un dependencia para la clase 'DateTime' denominada 'fecha'.
Podrá hacer uso de la misma de la siguiente forma:

```php
    $app = \DMS\Tornado\Tornado::getInstance();

    echo $app->fecha()->format('d/m/Y') . '<br />'; // forma de uso como método
    echo $app->fecha('2014-08-20')->format('d/m/Y') . '<br />'; // forma de uso como método con parámetros
    echo $app->fecha->format('d/m/Y') . '<br />'; // forma de uso como propiedad
```

##### Organización de proyecto:
Se recomienda organiza el proyecto de la siguiente forma:
- setear la configuración en el archivo "app/config/config.php
- setear los ganchos en el archivo "app/config/hook.php
- setear los enrutamientos en el archivo "app/config/route.php

Para esto el archivo index.php principal de la aplicación debería quedar de la 
siguiente forma:

```php
// se carga el core
require 'app/core/tornado.php';

// se cargan las configuraciones
require 'app/config/config.php';
require 'app/config/route.php';
require 'app/config/hook.php';

// se inicia el core
\DMS\Tornado\Tornado::getInstance()->run();
```

#### Módulos:

##### Controladores:
Todos los controladores deben extender de \DMS\Tornado\Controller y deben 
definir un namespace que respete la siguiente jerarquía: 
app\modules\[modulo]\controller

```php
    namespace app\modules\demo\controller;

    class Demo extends \DMS\Tornado\Controller {
        public function index($param = null){
            echo ' Hola ' . $param . '<br>';
        }
    }
```

La clase Controller ofrece los siguientes métodos:

```php

    // permite cargar un controlador
    $this->loadController('Modulo|Controlador');

    // permite cargar un modeo
    $this->loadModel('Modulo|Modelo');

    // permite cargar una vista sin parámetros
    $this->loadView('Modulo|Vista');

    // permite cargar una vista con parámetros
    $this->loadView('Modulo|Vista', array('clave'=>'valor'));

    // permite cargar una vista contenida dentro de una subcarpeta
    $this->loadView('Modulo|SubCarpeta/Vista');
```

##### Modelos:
Todos los controladores deben definir un namespace que respete la siguiente 
jerarquía: app\modules\[modulo]\model

```php
    namespace app\modules\demo\model;

    class Demo {
        public function getDemos($param = null){
            return true;
        }
    }
```

##### Vistas:
Los archivos de vistas deben tener la extensión .tpl.php - Ejemplo:
nombre.tpl.php

En caso de pasarse parámetros a las vistas la forma de invocar a los mismos es:
$nombreClave

###### Sugerencia para enlaces relativos y URL amigables:
Para que su sistema se ajuste rápidamente a un entorno de url amigables, puede 
definir, en el archivo de configuración, una constante llamada URLFRIENDLY 
con el valor de base para las rutas relativas, y luego utilizar la misma en la 
etiqueta base de html dentro del head. Ejemplo:

```php
    
    // usando .htaccess
    define('URLFRIENDLY', 'http://local.web/project/');

    // sin htaccess
    define('URLFRIENDLY', 'http://local.web/project/index.php?/');
```

```html
    <base href='<?=URLFRIENDLY?>' />

    <!-- ejemplo de uso -->
    <a href="./ruta">Link a ruta</a>
```

De esta forma con sólo editar el valor de dicha constante el sistema se ajustará 
automáticamente al uso o no de url amigables.

## Resumen de Métodos:

**DMS\Tornado\Tornado**

| Método | Detalle |
| ------ | ------- |
| getInstance() | Devuelve la instancia de Tornado (si no existe la crea) |
| run() | Arranca el core |
| config(string) | Devuelve el valor de la variable de configuración |
| config(string, mixed) | Setea el valor en la variable de configuración |
| autoload(bool) | Habilita/deshabilita el uso de autoload de clases |
| autolod(string, array) | Setea un namespace y las posibles ubicaciones de sus clases |
| error() | Devuelve la última excepción atrapada |
| error(bool) | Habilita/deshabilita el manejador interno de errores y excepciones |
| hook(string) | Ejecuta el gancho indicado |
| hook(string mixed) | Registra un gancho y su callback |
| route(string, mixed) | Registra un enrutamiento y su callback |
| addTypeParam(string, string) | Registra un nuevo tipo de parámetro |
| register(string, callable) | Registra una clase/servicio para extender la aplicación
| render(string) | Incluye una vista/template
| render(string, array) | Incluye una vista/template junto a un array de variables |
| param(string) | Devuelve el valor de un parámetro del enrutamiento |
| getRouteMatch | Devuelve la ruta que se esta procesando |
| forwardModule(string) | Delega la acción hacia otro módulo |
| forwardModule(string, array) | Delega la acción hacia otro módulo |
| forwardUrl(string) | Delega la acción hacia otra ruta |

**DMS\Tornado\Controller**

| Método | Detalle |
| ------ | ------- |
| loadController(string) | Incluye un controlador |
| loadModel(string) | Incluye un modelo |
| loadView(string) | Incluye una vista |
| loadView(string, array) | Incluye una vista junto a un array de variables |

## Licencia:

El proyecto se distribuye bajo la licencia MIT.

## Sugerencias:

Escriba a la dirección info@daniel.spiridione.com.ar
