<?php
namespace DMS\Tornado;

/**
 * Clase de auto carga de librerías
 * 
 * Parte del ejemplo del standar PSR-4
 * https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md
 * 
 * @package TORNADO-CORE
 * @author Daniel Martín Spiridione <info@daniel-spiridione.com.ar>
 * @link https://github.com/danielspk/TORNADO
 * @license https://github.com/danielspk/TORNADO/blob/master/LICENSE MIT License
 * @version 0.9.0
 */
final class Autoload
{
	
	/**
	 * Prefijos de namespaces y rutas de directorios correspondientes
	 * @var array
	 */
	private $_prefixes = array();
	
	/**
	 * Método contructor
	 */
	public function __construct() {
		$this->register();
	}
	
	/**
	 * Método que registra la clase para la auto carga de librerías
	 * @param boolean $pEnable Indica si debe habilitarse o deshabilitarse
	 * @return void
	 */
	public function register($pEnable = true)
	{
		if ($pEnable === true) {
			spl_autoload_register(array($this, 'loadClass'));
		} else {
			spl_autoload_unregister(array($this, 'loadClass'));
		}
	}
	
	/**
	 * Método que agrega un namespace y su directorio de base
	 * @param string $pPrefix Prefijo del namespace
	 * @param array $pBaseDir Rutas de directorios que contienen las clases
	 * @return void
	 */
	public function addNamespace($pPrefix, $pBaseDir)
	{
        $this->_prefixes[$pPrefix] = $pBaseDir;	
	}
	
	/**
	 * Método registrado para la autocarga de librerías
	 * Se encarga de cargar la clase solicitada.
	 * @param string $pClass Nombre del namespace\clase a cargar
	 * @return boolean
	 */
	public function loadClass($pClass)
	{

		// se hace una copia del nombre del namespace\clase
		$prefix = $pClass;
		
		// se recorre el namespace hacia atras en busca de una coincidencia
		// con el array de prefijos de namespaces
		while (false !== $pos = strrpos($prefix, '\\')) {

			// se obtiene el namespace
			$prefix = substr($pClass, 0, $pos);

			// se obtiene el nombre de la clase o directorio\clase
			$relativeClass = substr($pClass, $pos);

			// se trata de cargar la clase
			if($this->_loadMappedFile($prefix, $relativeClass)) {
				return true;
			}
		
			// se elimina una posición del namespace para una nueva búsqueda
			$prefix = rtrim($prefix, '\\');
			
		}
		
		// si no se pudo cargar ninguna clase
		return false;
		
	}
	
	/**
	 * Método que recorre el array de rutas del namespace y carga la clase
	 * @param string $prefix Nombre del prefijo de namespace
	 * @param string $relative_class Nombre del directorio\clase
	 * @return boolean
	 */
	private function _loadMappedFile($pPrefix, $pRelativeClass)
    {
		
        // se determina si el namespace existe como prefijo
        if (isset($this->_prefixes[$pPrefix]) === false) {
            return false;
        }

        // se recorren los directorios para ese namespace
        foreach ($this->_prefixes[$pPrefix] as $baseDir) {

            // se formatea la ruta del archivo a buscar
            $file = $baseDir . str_replace('\\', '/', $pRelativeClass) . '.php';

			// si existe el archivo se lo carga
			if (file_exists($file)) {
				require $file;
				return true;
			}
			
        }

        // si no se pudo cargar ninguna clase
        return false;
		
    }

}