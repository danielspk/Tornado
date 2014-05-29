<?php
namespace APP\Modules;

class Demo extends \DMS\Core\Controller {
	
	//put your code here
	public function index($param = null){
		
		//$pepep = 10 /0;
		echo ' Hola ' . $param . '<br>';
		
		echo '<form method="post"><input type="submit" value="enviar"/></form>';
	}
}
