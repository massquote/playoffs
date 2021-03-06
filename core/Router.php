<?php

/**
 * @Author: junnotarte
 * @Date:   2021-09-07 11:38:58
 * @Last Modified by:   junnotarte
 * @Last Modified time: 2021-09-07 13:38:38
 *
 * =================================================================================================
 * Handles the location of files inside the folders
 */

namespace Playoffs;

class Router {

	const DEFAULT_CLASS = 'Home';
	const DEFAULT_METHOD = 'index';

	private $requestUri = [];
	private $args = [];


	function __construct() {
		$this->requestUri  = explode('/', substr($_SERVER['REQUEST_URI'], 1));
	}

	/**
	 * this will check passed uri
	 * @return [type] [description]
	 */
	public function loadController() {

		$useClass = $this::DEFAULT_CLASS;
		$useMethod = $this::DEFAULT_METHOD;

		$directoryPath = systemPath('controller'); 

		if (count($this->requestUri) > 0 && !empty($this->requestUri[0])) {

			$useClass = ucfirst(strtolower($this->requestUri[0]));
			$useMethod = $this->requestUri[1] ?? $useMethod;


			if (strrpos($useMethod, '?')) {
				$pos = strrpos($useMethod, '?');
				$useMethod = substr($useMethod, 0, $pos);
				$useMethod = str_replace('/', '', $useMethod);
			}

			for($ctr = 2; $ctr < count($this->requestUri); $ctr++) {
				array_push($this->args, $this->requestUri[$ctr]);
			}

		}


		if (!file_exists($directoryPath . $useClass .'.php')) {
			$useClass = $this::DEFAULT_CLASS;
		}	

		require_once($directoryPath . $useClass .'.php'); 

		$class = new $useClass();

		if (empty($useMethod) || !method_exists($class, $useMethod)){
			$useMethod = $this::DEFAULT_METHOD;
		}

		call_user_func_array(array($class, $useMethod), $this->args);

	}
}