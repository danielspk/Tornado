# Change Log

## [2.0.0](https://github.com/danielspk/TORNADO/releases/tag/v2.0.0) (2015-06-20)

**Nuevas características:**

- Se configura a Tornado como Librería dentro de Composer (anteriormente era Proyecto)
- Pueden definirse n cantidad de un mismo tipo de hook (como si se tratase de middlewares).
- Se agregan ls siguientes variables de configuración: tornado_hmvc_use, tornado_hmvc_module_path y tornado_hmvc_serialize_path
- Nuevos métodos en la API: forwardModule, forwardUrl, addTypeParam, getRouteMatch y container
- La inyección de dependencias se realiza con el método container
- La inyección de dependencias acepta parámetros y registro como singleton

**Caracteristicas eliminadas:**

- Se elimina el esqueleto de aplicación inicial (se crea un nuevo proyecto en GitHub para esto)
- Se elimina la variable de configuración tornado_url_hmvc_deny
- Se impide el acceso directo a un módulo, controlador, método por URL - es obligatorio definir una ruta
- Se elimina la libreria de Autoload y sus métodos asociados - se sugiere el autoload de Composer
- Se eliminan métodos mágicos en clase Tornado para invocar dependencias inyectadas
- Métodos de la API eliminados: forward, autoload

## [1.0.0](https://github.com/danielspk/TORNADO/releases/tag/v1.0.0) (2014-12-05)

\* *Versión inicial*