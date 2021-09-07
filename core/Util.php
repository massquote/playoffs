<?php

/**
 * @Author: junnotarte
 * @Date:   2021-09-07 11:35:41
 * @Last Modified by:   junnotarte
 * @Last Modified time: 2021-09-07 13:39:05
 *
 * =================================================================
 * Any global function that can be use in the entire system
 */


/**
 * Just to get the path of the file
 * @param  string $folder [description]
 * @return [type]         [description]
 */
function systemPath (string $folder) : string {
	return $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR .$folder . DIRECTORY_SEPARATOR;
}
