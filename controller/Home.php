<?php

/**
 * @Author: junnotarte
 * @Date:   2021-09-07 12:10:44
 * @Last Modified by:   junnotarte
 * @Last Modified time: 2021-09-07 12:16:25
 */

use Playoffs\View;

class Home extends View{

	public function index() {

		$this->loadView('home');

	}
}