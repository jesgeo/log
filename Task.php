<?php

	namespace Log;
	
	class Task extends Action {

		var $actions = array();


		public function addAction($a){
			$this->actions[] = $a;
		}

		public function setStatus($a){
			parent::setStatus($a);
			$this->calcProgress();
		}

		public function calcProgress() {
			$p = 0;
			foreach ($this->actions as $a)
				$p += ($a->progress === false) ? 100 : $a->progress;

			if(count($this->actions) != 0)
				$this->progress = (int) $p/count($this->actions);
			return $this->progress;
		}

		public function __toString() {
			$str = parent::__toString();
			foreach ($this->actions as $a)
				$str .= "\t$a";
			return (string)$str;
		}

	}

?>