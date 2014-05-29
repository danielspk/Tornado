TORNADO
============

*ESTE PROYECTO SE ENCUENTRA EN DESARROLLO - AÚN NO EXISTE UNA VERSIÓN FUNCIONAL!*

TORNADO es un reducido marco de trabajo para PHP que permite implementar el 
patrón HMVC y/o servicios RESTfull

### Filosofia:

TORNADO no intenta ser un gran framework PHP. Contrariamente intenta ser un 
núcleo de trabajo muy reducido para implementar patrones de arquitectura HMVC 
y/o servicios REST, con la menor parametrización y utilización de código posible.

TORNADO esta pensado para programadores con conocimientos reales de PHP. La 
filosofía del core es la no preguntar cada vez, sino la de parametrizar una 
única vez. Esto quiere decir que el core no se encuentra ejecutando cientos de 
sentencias condicionales en cada ejecución, ya que se asume que el programador 
parametrizará lo que necesite y que el core no lo hara por el condicionalmente 
todo el tiempo. Esto se reduce en mayor velocidad de ejecución y menor cantidad 
de código a mantener. Por este mismo motivo, el core tampoco hace uso de 
sentencias adicionales para ajustar parámetros de entrada en sus métodos o 
validaciones adicionales - se asume que el programador experimentado respetará 
la documentación del core.

TORNADO tampoco incluye librerías de soporte para tareas comunes como acceso a 
base de datos, gestión de plantillas, envío de mais, etc.
Para esto el core ofrece, mediante composer, la posibilidad de incluir librerías 
de terceros para extender sus características de acuerdo a las necesidades del 
proyecto a desarrollar.

#####Nota de Autor:
>Sección de comentario sin editar:
Compartimos en gran medida la filosofía del "MicroPHP Manifiesto" 
(http://microphp.org), y reforzamos la idea de que existe una herramienta 
indicada para cada trabajo. Somos conscientes de que existen miles de librerías 
que hacen muy bien su trabajo y entendemos que no tiene sentido reinventar la 
rueda en creando un nuevo ORM o un gestor de plantillas propios, ya que 
justamente contradice la filosofía del core. Incorporamos un set de liberías 
(herramientas) básicas para tareas simples. Alentamos el uso de frameworks o 
librerías más potentes cuando las condiciones del proyecto lo requieran. Es 
verdad que todos los proyectos pueden crecer en complejidad y tamaño con el 
tiempo, esta sería una razón para utilizar un completo framework desde el 
comienzo, sin embargo como todo extremo consideramos que es malo. Somos 
partidarios de usar un marco simple que sirva de organización y utilizar las 
librerías que se necesiten (no crearlas porque si ya que la reutilización hace 
que sea código probado). Personalmente me he encontrado situaciones en donde se 
usa un framework que incluye cientos de caracteristicas que no se usan en el 
proyecto y encima luego hay que salir a buscar librerías de terceros para 
cumplir ciertos trabajos. Terminamos subiendo varios megas de código fuente de 
los cuales sólo utilizamos una parte, haciendo proyectos lentos (no nos 
olvidemos que al día de la fecha PHP es interpretado) y terminando de aprender 
como se usa cada framework los cuales con las versiones a veces se hacen muy 
imcompatibles. Si usamos un núcleo muy básico (sin gran arquitectura) y 
librerías y las mismas evolucionan o se deprecan a lo sumo debemos rescribir 
ciertas funcionalidades y no todo el proyecto. Trabajo en empresas y no hay nada 
mas tedioso que hacer toda una reingenieria o perder varias horas aprendiendo 
cosas nuevas con la complejidad de tener que mantener versiones con personal 
propio.

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
