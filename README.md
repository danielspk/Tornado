TORNADO
============

**ESTE PROYECTO SE ENCUENTRA EN DESARROLLO**

TORNADO es un reducido marco de trabajo para PHP que permite implementar el 
patrón HMVC y/o servicios RESTfull

### Su nombre:

TORNADO es el nombre que mejor se ajusta a la filosofía ser core: "ser rápido" 

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
- tener la menor cantidad de métodos posibles en su API
- permitir incluir ganchos para que los programadores puedan extender el 
mismo
- permitir incluir librerías de terceros con suma facilidad
- ser ligero respecto a la cantidad de líneas de código a mantener _(por esta 
razón no tiene una compleja arquitectura de diseño)_
- ser un core de trabajo _(NUNCA un framework)_

## Características:

- Enrutamientos para utilizar módulos HMVC y/o servicios REST (apoyado en URL 
amigables)
- Configuración de la aplicación
- Ganchos para extender las características del core
- Captura de errores y excepciones
- Auto carga de librerías de terceros

### Codificación:

TORNADO apoya la idea del PHP Framework Interop Group e implementa los 
estándares PSR-1, PSR-2 y PSR-4.

### Dependencias externas (opcionales):

- DMS Libs for PHP (en desarrollo)

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
modificar las líneas 4 y 5 de acuerdo a la ubicación del proyecto en el servidor 
y las restricciones que quiera aplicar a los redireccionamientos.

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
    $app->route('HTTP', "/", "demo@demo@index");
    
    // enrutamiento a función anónima
    $app->route(array(
        "/saludar/:string"	=> function($pNombre = null){
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

##### Setear namespace de autoload:

```php
    $app = DMS\Tornado\Tornado::getInstance();

    $app->autoload()->addNamespace('Twing\Twing', array('twing/lib/src'));
```

##### Definir Hooks:
Existen 4 tipos de hooks:
- init: antes de cargar un módulo
- end: despues de ejecutar un módulo
- 404: al producirse un error http de tipo 404
- error: al atraparse un error de aplicación

```php
    $app = DMS\Tornado\Tornado::getInstance();

    // utilizando un módulo / clase
    $app->hook('error', array('namespace\clase', 'metodo'));

    // utilizando una función anónima
    $app->hook('404', function(){
        echo '404';
    });
```

##### Definir Enrutamientos:
Los enrutamientos pueden ser:
- HTTP - cualquier petición
- GET - RESTfull método GET
- POST - RESTfull método POST
- PUT - RESTfull método PUT
- DELETE - RESTfull método DELETE

En caso de que el servidor no soporte los métodos PUT y DELETE se pueden simular 
los mismos enviando una petición POST con una variable "REST_METHOD" cuyo valor 
sea PUT o DELETE

Existen tres tipos de parámetros para enrutar una URL:
- :string - sólo acepta letras
- :number - sólo acepta números
- :alpha - acepta números y letras

```php
    $app = DMS\Tornado\Tornado::getInstance();

    // utilizando un módulo
    $app->route('HTTP', "/", "demo@demo@index");

    // utilizando una función anónima
    $app->route(array(
        "/saludar/:alpha" => function($pNombre = null){
            echo 'Hola ' . $pNombre;
        }
    ));
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

## Licencia:

El proyecto se distribuye bajo la licencia MIT.

## Sugerencias:

Escriba a la dirección info@daniel.spiridione.com.ar
