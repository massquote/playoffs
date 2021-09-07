<?php

/**
 * @Author: junnotarte
 * @Date:   2021-09-07 11:32:27
 * @Last Modified by:   junnotarte
 * @Last Modified time: 2021-09-07 13:10:42
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);


$configDir = 'config';
$configFolder    = scandir($configDir);

foreach ($configFolder as $configFile) {
	if (!in_array($configFile, ['.', '..']))
		require_once $configDir . DIRECTORY_SEPARATOR . $configFile;
}
require_once('vendor/autoload.php');

require_once 'core/Util.php';
require_once 'core/Router.php';
require_once 'core/View.php';
require_once 'core/Model.php';

require_once 'lib/Formater.php';


// for now lets add the model here 
require_once 'models/Export_model.php';




use Playoffs\Router;

$routes = new Router();
$routes->loadController();