<?php
/**
 * NOTICE OF LICENSE
 *
 * Copyright Harald Huber
 * You are allowerd to use this module in one project (also commercial).
 * You are not allowed to share or sell this software.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * INFO
 *
 * @author    Harald Huber
 * @copyright Harald Huber
 * @license   freeeeee
 * @www       http://huber.systems/
 * @for       PS Version 1.7
 *
 * Run manually:
 * http://domain.com/modules/modulName/Cron_Prestashop_Run_File_Without_Module.php
 *
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');

/*Cache-Control:*/
header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
header("Pragma: no-cache"); //HTTP 1.0
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

$GLOBALS['context'] = Context::getContext();

class singleFile 
{
	public function run() 
	{
   		# ... do something ...
		#$sqlAllTables = "SHOW TABLES";
		#$tables = Db::getInstance()->executeS($sqlAllTables);
	}
}

$r = new singleFile();
$r->run();

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
  <body>
  <div class="container-fluid">
	<div class="row">
		<div class="col-xs-6">
			<h1>Output for something</h1>
		</div>
	</div>
  </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  </body>
</html>
