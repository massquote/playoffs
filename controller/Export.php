<?php

/**
 * @Author: junnotarte
 * @Date:   2021-09-07 12:23:35
 * @Last Modified by:   junnotarte
 * @Last Modified time: 2021-09-07 13:33:00
 */
use Playoffs\View;
use Playoffs\Model\Export_model;
use Playoffs\Lib\Formater;


class Export extends View{
	private $exportDb;
	private $formater;

	private $args = [];
	private $format = 'html';


	function __construct() {
        $this->exportDb = new Export_model();
        $this->formater = new Formater();
    }

    /**
     * playerstats type
     * @return [type] [description]
     */
	public function playerstats() {
		$this->setFilter();
		$data = $this->exportDb->filter($this->args)->getPlayerStats();
		$funcname = $this->format;
		
		if (strtolower($this->format) == 'html') {
			$this->loadView('export', $this->formater->$funcname($data));
			return;
		}

		echo  $this->formater->$funcname($data);
	}

	/**
	 * player type
	 * @return [type] [description]
	 */
	public function players() {
		$this->setFilter();
		$data = $this->exportDb->filter($this->args)->getPlayers();
		$funcname = $this->format;
		
		if (strtolower($this->format) == 'html') {
			$this->loadView('export', $this->formater->$funcname($data));
			return;
		}

		echo  $this->formater->$funcname($data);
	}


	/**
	 * just to set the uri args
	 */
	private function setFilter() {
		$args = collect($_REQUEST);
		$this->format = strtolower($args->pull('format')) ?: 'html';
        $this->args = $args;
	}


}