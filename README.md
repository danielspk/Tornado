TORNADO
============

**ESTE PROYECTO SE ENCUENTRA EN DESARROLLO**

TORNADO es un reducido marco de trabajo para PHP que permite implementar el 
patrón HMVC y/o servicios RESTfull

### Filosofia:

TORNADO no intenta ser un gran framework PHP. Contrariamente intenta ser un 
núcleo de trabajo muy reducido para implementar patrones de arquitectura HMVC 
y/o servicios REST, con la menor parametrización y utilización de código 
posible, apoyado en un core que organice el proyecto y un sistema de 
configuración y gestión de errores simple.

TORNADO no incluye librerías de soporte para tareas comunes como acceso a base 
de datos, gestión de plantillas, envío de mais, etc.
Para esto el core ofrece, mediante composer, la posibilidad de incluir librerías 
de terceros para extender sus características de acuerdo a las necesidades 
particulares del proyecto a desarrollar.

Puede obtener más información de la filosofía del core mirando la wiki del 
proyecto.

### Inspiración:

TORNADO se inspiro en varios microframeworks PHP, entre ellos cabe mencionar:

- Toro - http://toroweb.org/
- Flight - http://flightphp.com/
- Shield - https://github.com/enygma/shieldframework
- Slim - http://www.slimframework.com/
- Fat-Free Framework - http://fatfreeframework.com/home

### Metas:

TORNADO se desarrollo tratando de respetar las siguiente metas:

- ser rápido
- ser fácil de entender _(tanto su API como su construcción interna)_
- tener la menor cantidad de métodos posibles dentro de su API
- permitir incluir ganchos para que los programadores puedan extender el mismo
- permitir incluir librerías de terceros con suma facilidad
- ser ligero respecto a la cantidad de líneas de código a mantener
- ser un core de trabajo _(NUNCA un framework)_

## Características:

- Enrutamientos para utilizar módulos HMVC y/o servicios REST (apoyado en URL 
amigables)
- Configuración de la aplicación
- Ganchos para extender las características del core
- Captura de errores y excepciones
- Auto carga de librerías de terceros

### Codificación:

TORNADO apoya la iniciativa del PHP Framework Interop Group e implementa los 
estándares PSR-1, PSR-2 y PSR-4.

## Instalación:

El core en su versión nativa no requiere instalación alguna. Basta con descargar
el proyecto y copiarlo en alguna ubicación del servidor web. En caso de querer
incluir las dependencias de terceros debe usar "composer" para su instalación:

1. Instale composer ( puede obtener ayuda en https://getcomposer.org/download/ )

2. Edite el archivo composer.json para incluir las librerías necesarias.

3. Inicie la consola de comando en la ubicación del proyecto y ejecute

```
composer install --prefer-dist
```

4. En caso de querer utilizar URL amigables editar el archivo .htaccess y 
modificar las líneas 4 y 5 de acuerdo a la ubicación del proyecto dentro del 
servidor y las restricciones que quiera aplicar a los redireccionamientos.

## Manual de uso:

#### Uso básico:
Ejemplo de uso básico (con dos tipos de enrutamientos)

```php
<?php

    // incluir el core
    require 'app/core/tornado.php';
    
    // obtener instancia del core
    $app = DMS\Tornado\Tornado::getInstance();
    
    // enrutamiento a módulo desde raíz
    $app->route('/', 'demo\demo\index');
    
    // enrutamiento a función anónima
    $app->route(array(
        '/saludar/:string'	=> function($pNombre = null){
            echo 'Hola ' . $pNombre;
        }
    ));

    // arrancar el core
    $app->run();
    
```

#### API:

##### Obtener Instancia del core:

```php
    $app = DMS\Tornado\Tornado::getInstance();
```

##### Arrancar el core:

```php
	// con una instancia del core en una variable
    $app = DMS\Tornado\Tornado::getInstance();
    $app->run();

    // sin ninguna instancia del core en una variable
    DMS\Tornado\Tornado::getInstance()->run();
```

##### Setear configuración:

```php
    $app = DMS\Tornado\Tornado::getInstance();

    $app->config('nombre', 'valor del nombre');
    $app->config('nombres', array('nombre1'=>'valor1', 'nombre2'=>'valor2'));
```

##### Leer configuración:

```php
    $app = DMS\Tornado\Tornado::getInstance();

    // configuración simple
    echo $app->config('nombre');

    // configuración array
    $nombres = $app->config('nombres');
    echo $nombres[0]['nombre1'];
    echo $nombres[1]['nombre2'];
```

##### Habilitar/deshabilitar autoload y setear namespaces:

```php
    $app = DMS\Tornado\Tornado::getInstance();

    $app->autoload(true); // false para deshabilitar
    $app->autoload('Twing\Twing', array('twing/lib/src', 'twing/lib/test'));
```

##### Uso de Hooks:
Existen 4 tipos de hooks:
- init: antes de cargar un módulo
- end: despues de ejecutar un módulo
- 404: al producirse un error http de tipo 404
- error: al atraparse un error de aplicación

```php
    $app = DMS\Tornado\Tornado::getInstance();

    // utilizando un módulo / clase
    $app->hook('error', 'modulo\controlador\metodo'));

    // utilizando una función anónima
    $app->hook('404', function(){
        echo '404';
    });
```

La forma de ejecutar un gancho por código es la siguiente:

```php
    $app = DMS\Tornado\Tornado::getInstance();

    $app->hook('fueraDeLinea');
```

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

Existen tres tipos de parámetros para enrutar una URL:
- :string - sólo acepta letras
- :number - sólo acepta números
- :alpha - acepta números y letras

En caso de incluir parámetros opcionales la sintaxis es la siguiente:
- [/:string]
- [/:number]
- [/:alpha]

```php
    $app = DMS\Tornado\Tornado::getInstance();

    // utilizando un módulo y cualquier tipo de petición
    $app->route('/', 'demo\demo\index');

    // utilizando una función anónima y cualquier tipo de petición
    $app->route('/saludar/:alpha', function($pNombre = null) {
        echo 'Hola ' . $pNombre;
    });

    // utilizando parámetros opcionales y cualquier tipo de petición
    $app->route('/mostrar[/:alpha][/:number]', function ($pNombre = null, $pEdad = null) {
        echo 'Hola ' . $pNombre . ', ' . $pEdad;
    });

    // utilizando un módulo y petición POST
    $app->route('POST /', 'demo\demo\guardar');

    // utilizando un módulo y petición GET o POST
    $app->route('GET|POST /', 'demo\demo\listar');
```

El único enrutamiento obligatorio es el del nodo raíz ya que indica cuál será el 
callback a ejecutar por defecto al ingresar a la aplicación.
Puede usar la convención de nombres de los módulos MVC para enrutar su 
aplicación. Por ejemplo el módulo *"usuarios"*, controlador *"acceso"*, método 
*"ingresar"* puede ser accedido directamente por URL de la siguiente forma:

(http://dominio/index.php?/usuarios/acceso/ingresar)

En caso de utilizar parámetros el criterio es el mismo que en los enrutameintos 
definidos por métodos. Los mismos se separarán por barras y se colocaran a la 
derecha del método a invocar:

http://dominio/index.php?/usuarios/listado/buscar/param1/param2

En caso de no indicarse el método a ejecutar por defecto se invocará al método 
"index" del controlador:

http://dominio/index.php?/usuarios/acceso

es igual a 

http://dominio/index.php?/usuarios/acceso/index

Otra forma reducida de acceso es utilizar un único nombre (válido siempre que no 
existan parámetros):

http://dominio/index.php?/nombre

es igual a 

http://dominio/index.php?/nombre/nombre/index

##### Gestión de errores y excepciones:
El manejo de errores y excepciones viene habilitado por defecto. Puede alterar 
su comportamiento de la siguiente forma:

```php
    $app = DMS\Tornado\Tornado::getInstance();

    $app->error(true);  // habilita el manejador
    $app->error(false); // deshabilita el manejador
```

Puede acceder a la última excepción lanzada de la siguiente forma:

```php
    $app = DMS\Tornado\Tornado::getInstance();

    $exc = $app->error();
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
DMS\Tornado\Tornado::getInstance()->run();
```

#### Módulos:

##### Controladores:
Todos los controladores deben extender de \DMS\Tornado\Controller y deben 
definir un namespace que respete la siguiente jerarquía: 
App\Modules\[Modulo]\Controller

```php
    namespace App\Modules\Demo\Controller;

    class Demo extends \DMS\Tornado\Controller {
        public function index($param = null){
            echo ' Hola ' . $param . '<br>';
        }
    }
```

La clase Controller ofrece los siguientes métodos:

```php

    // permite cargar un controlador
    $this->loadController('Modulo\Controlador');

    // permite cargar un modeo
    $this->loadModel('Modulo\Modelo');

    // permite cargar una vista sin parámetros
    $this->loadView('Modulo\Vista');

    // permite cargar una vista con parámetros
    $this->loadView('Modulo\Vista', array('clave'=>'valor'));
```

##### Vistas:
Los archivos de vistas deben tener la extensión .tpl.php - Ejemplo:
nombre.tpl.php

En caso de pasarse parámetros a las vistas la forma de invocar a los mismos es:
$nombreClave

###### Sugerencia para enlaces y URL amigables:
Para que su sistema se ajuste rápidamente a un entorno de url amigables o no, 
puede definir, en el archivo de configuración, una constante llamada URLFRIENDLY 
con el valor "/" si usa url amigables o "index.php/" si no utiliza. Ejemplo:

```html
    <a href="<?=URLFRIENDLY?>nombre">enlace</a>
```

De esta forma con sólo editar el valor de dicha constante el sistema se ajustará 
automáticamente al uso o no de url amigables.

## Licencia:

El proyecto se distribuye bajo la licencia MIT.

## Sugerencias:

Escriba a la dirección info@daniel.spiridione.com.ar
