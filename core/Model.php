<?php

/**
 * @Author: junnotarte
 * @Date:   2021-09-07 11:41:37
 * @Last Modified by:   junnotarte
 * @Last Modified time: 2021-09-07 12:04:07
 * =============================================================
 *
 * Code from previous file include/utils.php
 */

namespace Playoffs;


class Model {

	private $db;


	function __construct(){
		$this->setConnection();
	}

	function __destruct(){
		mysqli_close($this->db);
	} 

	/**
	 * connection to db
	 */
	private function setConnection(){
		$config = $GLOBALS['CONFIG']['database'];
		$this->db = @mysqli_connect($config['host'], $config['user'], $config['paswd'], $config['database']);

		if (!$this->db) {
			exit('Model Error: ' . mysqli_connect_error());
		}
	}

	/**
	 * Execute a query & return the resulting data as an array of assoc arrays
	 * @param string $sql query to execute
	 * @return boolean|array array of associative arrays - query results for select
	 *     otherwise true or false for insert/update/delete success
	 */
	function query($sql) {
		$dataTable = mysqli_query($this->db, $sql);

		$data = [];
		while ($row = mysqli_fetch_assoc($dataTable)) {
			 $data[] = $row;
		}

		mysqli_free_result($dataTable);
		return $data;
	}



}