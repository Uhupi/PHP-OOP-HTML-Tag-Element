<?php

class Uhupi_Layout_Abstract {
	
	public $request;
	
	public function __construct() {
		
		global $request;
		
		$this->request = $request;
//		$this->view = new view($request);
		
	}
}

?>