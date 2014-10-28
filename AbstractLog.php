<?php
	namespace Log;

	define('ROOT', __DIR__ . DIRECTORY_SEPARATOR);

	function __autoload($classname) {
	    $namespace = substr($classname, 0, strrpos($classname, '\\'));
	    $namespace = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
	    $classPath = ROOT . $namespace . '.php';

	    $namespace = str_replace(__NAMESPACE__.'\\', "", $classname);
	    $wclass = ROOT . $namespace . '.php';

	    if(is_readable($classPath)) 
	        require_once $classPath;
	    if(is_readable($wclass)) 
	        require_once $wclass;
	}

	spl_autoload_register('Log\__autoload');
	

	abstract class AbstractLog implements LogInterface {
		var $tasks = array();

		public function addTask($t) {
			if ($t instanceOf Task) 
				$this->tasks[] = $t;
			else {
				throw new ErrorException ('Instance is not a Task object');
				return false;
			}
		}



	}




	// echo ROOT;

?>