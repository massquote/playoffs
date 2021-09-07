<?php

/**
 * @Author: junnotarte
 * @Date:   2021-09-07 12:04:27
 * @Last Modified by:   junnotarte
 * @Last Modified time: 2021-09-07 13:39:33
 * =================================================================
 *
 * To locate the view files in the folder
 */
namespace Playoffs;


class View {

	private $pageTemplate = '_default.php';


	function __construct() {}

	public function loadView(string $template, array $renderData = []) {
		$data = $renderData;
		$partialFile = $this->getPartial($template);
		$directoryPath = systemPath('views');

		require_once $directoryPath . $this->pageTemplate;
	}

	public function getPartial(string $template) : string {
		$directoryPath = systemPath('views');
		return $directoryPath.$template.'.php';

	}


}