<?php
	namespace Log;

	class Action {
		
		var $title, $desc; 
		
		var $progress = false;
		var $status = null;

		private $datetime = null;
		var $_storage = null;


		public function __construct($title, $description = null, $status = null) {
			$this->datetime = date("now");

			$this->title = $title; 
			$this->desc = $description; 
			$this->status = $status; 
			return $this;
		}

		public function setProgress($p){
			$this->progress = $p;
		}

		public function getProgress(){
			return $this->progress;
		}

		public function setStatus($s){
			$this->status = $s;
		}

		public function getStatus(){
			return $this->status;
		}

		public function iProg($p = 1) {
			$this->progress += $p;
		}

		public function __toString() {
			$p = ($this->progress !== false) ? " (".$this->progress.")" : "";
			return $this->title . " > " . $this->status . $p . PHP_EOL;
		}

	}

?>