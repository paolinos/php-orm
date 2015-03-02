<?php
class DBResult
{
	public $valid = true;
	public $message = "";
	public $data = null;

	public function error($msg)
	{
		$this->valid = false;
		$this->message = $msg;
	}
}
?>