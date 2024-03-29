<?php
// We start our sessions
session_start();
/*echo '<pre>';
print_r($_SESSION);
echo '</pre>';*/
// setup some definitions
// The root path,
define( "FRAMEWORK_PATH", dirname( __FILE__ ) ."/" );
// require our registry
require_once('registry/registry.class.php');
// require our registry
require_once('registry/registry.class.php');
$registry = PHPAppRegistry::singleton();
$registry->getURLData();
// get database connection details
require_once('config.php');
// store core objects in the registry.
$registry->storeObject('mysql.database', 'db');
$registry->storeObject('template', 'template');
$registry->storeObject('authentication', 'authenticate');
$registry->storeSetting('default','view');
// create a database connection
$registry->getObject('db')->newConnection($config['db_ecom_host'], $config['db_ecom_user'], $config['db_ecom_pass'], $config['db_ecom_name']);
// check post data for users trying to login, and session data for users who are logged in
$registry->getObject('authenticate')->checkForAuthentication();
// set the default skin setting (we will store these in the database later...)
$registry->storeSetting('default', 'skin');
$registry->storeSetting(1, 'default_shipping_method');
// populate our page object from a template file
$registry->getObject('template')->buildFromTemplates('header.tpl.php', 'main.tpl.php');
//require_once('Views/default/templates/header.tpl.php');
//require_once('Views/default/templates/content.tpl.php');
//require_once('Views/default/templates/footer.tpl.php');
/*print_r('<pre>');
print_r($registry->getObject('template'));
print_r('</pre>');*/
$activeControllers = array();
$registry->getObject('db')->executeQuery('SELECT controller FROM controllers WHERE active=1');
while( $activeController = $registry->getObject('db')->getRows() )
{
	$activeControllers[] = $activeController['controller'];
}
/*print_r('<pre>');
print_r($activeControllers);
print_r('</pre>');*/
$currentController = $registry->getURLBit( 0 );
if( in_array( $currentController, $activeControllers ) )
{
	require_once( FRAMEWORK_PATH . 'controllers/' . $currentController . '/controller.php');
	$controllerInc = $currentController.'controller';
	$controller = new $controllerInc( $registry, true );
}
else
{
	require_once( FRAMEWORK_PATH . 'controllers/page/controller.php');
	$controller = new Pagecontroller( $registry, true );
	//print_r('Pagecontroller called...');
}
// parse it all, and spit it out
$registry->getObject('template')->parseOutput();
print $registry->getObject('template')->getPage()->getContent();

exit();
?>
