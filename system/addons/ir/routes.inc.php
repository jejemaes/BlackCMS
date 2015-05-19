<?php
/**
 * Maes Jerome
 * routes.inc.php, created at May 13, 2015
 *
 */

$app = App();
$ir_controller = 'addons\ir\controller\IrController';

$app->addRoute('/ir/bundle/:type/:name', $ir_controller . ':bundleAction', 'GET', 'none', array('name' => '[a-z.]{3,}', 'type' => '(css|js)'));

$app->addRoute('/ir/schema/', $ir_controller . ':schemaBuilderAction', 'GET', 'none');
