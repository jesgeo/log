<?php
	namespace Log;

	// an interface for logging > promisingly having the ability to save in file/database
	//	+ subsequent abstract methods

	interface LogInterface {

		/**	Adding Task instance
			@throws: if type is a mismatch
		*/
		public function addTask($t);
	}

?>