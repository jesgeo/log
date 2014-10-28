<?php

namespace Log;

// error_reporting(-1);

// an interface for logging > promisingly having the ability to save in file/database
//	+ subsequent abstract methods

interface LogInterface {

	/**
		Adding Task instance
		@throws: if type is a mismatch
	*/
	public function addTask($t);
}



abstract class Log implements LogInterface {
	var $tasks = array();

	public function addTask($t) {
		if ($t instanceOf Task) 
			$this->tasks[] = $t;
		else {
			throw new Exception ('Instance is not a Task object');
			return false;
		}
	}
}


// progress should be 100% value
class AccountBatchLog extends Log {

	private function getStyleFromStatus($s){
		$cls = '';

		switch($s){
			case 'DONE':
			case 'SUCCESS':
			case 'FOUND':
				$cls = 'success';
				break;
			case 'FAILED':
			case 'DUPLICATE FOUND':
				$cls = 'failed';
				break;
			case 'CANCELLED':
			case 'ABANDONED':
				$cls = 'catastrophic';
				break;
			case 'NOT FOUND':
				$cls = 'warning';
				break;
			default:
				$cls = 'step';
		}

		return $cls;

	}

	public function __toString() {

		if (count($this->tasks) <= 0) return "";

		$str = "<table class='log' style=''>".PHP_EOL;

		foreach ($this->tasks as $t) {

			$cls = $this->getStyleFromStatus($t->status);


			$str .= "<tbody><tr class='task $cls' title='{$t->desc}'>".PHP_EOL;
			$str .= "<td data-icon='&#xe6ab;' class='expand'></td>".PHP_EOL;
			$str .= "<td>".$t->title."</td>".PHP_EOL;
			$str .= "<td class='status'>".$t->status."</td>".PHP_EOL;
			$str .= "<td class='progress'>".
						(ctype_digit($t->progress) ?  $t->progress.'%' : '')." </td>".PHP_EOL;
			$str .= "</tr>".PHP_EOL;

			foreach ($t->actions as $a) {
				$str .= "<tr class='action ".$this->getStyleFromStatus($a->status)."' 
								title='".str_replace("'", "", $a->desc)."'>".PHP_EOL;
				$str .= "<td></td>".PHP_EOL;
				$str .= "<td>".$a->title."</td>".PHP_EOL;
				$str .= "<td class='status'>".$a->status."</td>".PHP_EOL;
				$str .= "<td class='progress'>".
							(ctype_digit($a->progress) ?  $a->progress.'%' : '')." </td>".PHP_EOL;
				$str .= "</tr>";
			}
			$str .= "</tbody>".PHP_EOL;
		}

		$str .= "</table>".PHP_EOL;

		return $str;
	}



}



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

// echo "new ";

// var_dump($tks);


?>