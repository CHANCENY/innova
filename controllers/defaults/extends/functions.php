<?php
/**
 * Common to all site
 */
use Innova\Modifier\Modifier;

$controller = active_controller();
Modifier::setTitle($controller->getRouteName());