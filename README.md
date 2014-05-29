TORNADO
============

**ESTE PROYECTO SE ENCUENTRA EN DESARROLLO - AÚN NO EXISTE UNA VERSIÓN FINAL**

TORNADO es un reducido marco de trabajo para PHP que permite implementar el 
patrón HMVC y/o servicios RESTfull

### Filosofia:

TORNADO no intenta ser un gran framework PHP. Contrariamente intenta ser un 
núcleo de trabajo muy reducido para implementar patrones de arquitectura HMVC 
y/o servicios REST, con la menor parametrización y utilización de código 
posible.

TORNADO esta pensado para programadores con conocimientos reales de PHP. La 
filosofía del core es la no preguntar cada vez, sino la de parametrizar una 
única vez. Esto quiere decir que el core no se encuentra ejecutando cientos de 
sentencias condicionales en cada ejecución, ya que se asume que el programador 
parametrizará lo que necesite y que el core no lo hara por él condicionalmente 
todo el tiempo. Esto se reduce en mayor velocidad de ejecución y menor cantidad 
de código a mantener. Por este mismo motivo, el core tampoco hace uso de 
sentencias adicionales para ajustar parámetros de entrada en sus métodos 
internos - se asume que el programador experimentado respetará la documentación 
del core.

TORNADO no incluye librerías de soporte para tareas comunes como acceso a base 
de datos, gestión de plantillas, envío de mais, etc.
Para esto el core ofrece, mediante composer, la posibilidad de incluir librerías 
de terceros para extender sus características de acuerdo a las necesidades 
particulares del proyecto a desarrollar.

#####Nota del Autor:
>Comparto en gran medida la filosofía del "MicroPHP Manifiesto" 
(http://microphp.org), y refuerzo la idea de que existe una herramienta 
indicada para cada trabajo. Soy muy conciente de que existen miles de librerías 
que hacen muy bien su trabajo y apoyo su usos. También aliento al uso de 
frameworks cuando las condiciones del proyecto lo requieran. Se que es verdad 
que todos los proyectos pueden crecer en complejidad y tamaño con el tiempo, 
tal vez esta sería una razón para utilizar un completo framework desde el 
comienzo. Sin embargo como todo extremo considero que esto es malo. Soy más 
partidario de usar un marco simple que sirva para organización del proyecto y el 
utilizar librerías reconocidas de terceros. Personalmente me he encontrado en 
proyecto que utilizaron frameworks reconocidos que incluyen cientos de 
caracteristicas que nunca fueron y/o serán utilizadas. Incluso a pesar del gran 
set de módulos fue necesario importar librerías de terceros para cumplir ciertos 
procesos, produciendo una pésima organización de código (Ejemplo: para validar 
formularios se usa un mix de librerías nativas y otras de terceros o personales). 
En definitiva se terminan subiendo varios megas de código fuente de los cuales 
sólo se utilizan una parte y terminando de aprender como se usa cada framework 
los cuales con las versiones a veces se hacen muy imcompatibles entre sí. Si 
usamos un núcleo muy básico (sin una gran arquitectura por detrás) y librerías 
reconocidas, si las mismas evolucionan en el futuro o se deprecan a lo sumo 
debemos rescribir ciertas funcionalidades específicas.
Dicho esto me gustan los framework, facilitan muchas cosas, los uso y los 
seguiré utilizando, pero como expuse al principio hay una herramienta para 
cada trabajo. Cuando necesito un framework lo uso, y cuando necesito un núcleo 
simple de trabajo utilizo uno que pueda extender con facilidad y que no 
incorpore características por demas (si necesito algo lo incluyo, sino no lo 
quiero ahí en donde no tiene que estar)

## Características:

- Patón HMVC apoyado en URL amigables
- Enrutamientos para servicios REST
- Configuración de la aplicación
- Ganchos para extender las características del core
- Tratamiento de errores y excepciones
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

## Manual de uso:

En construcción (no disponible).....

## Licencia:

El proyecto se distribuye bajo la licencia MIT.

## Sugerencias:

Escriba a la dirección info@daniel.spiridione.com.ar
