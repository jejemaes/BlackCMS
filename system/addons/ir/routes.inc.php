<?php
/**
 * Maes Jerome
 * routes.inc.php, created at May 13, 2015
 *
 */

$app = App();
$ir_controller = 'addons\ir\controller\IrController';

$app->addRoute('/ir/bundle/:type/:name', $ir_controller . ':bundleAction', 'GET', 'public', array('name' => '[a-z.]{3,}', 'type' => '(css|js)'));

$app->addRoute('/ir/schema/', $ir_controller . ':schemaBuilderAction', 'GET', 'public');
$app->addRoute('/ir/test/', $ir_controller . ':IndexAction', 'GET', 'public');
