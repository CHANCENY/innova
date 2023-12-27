<?php

use Innova\Modifier\Modifier;
use Innova\modules\Site;

$controller = active_controller();
Modifier::setTitle($controller->getRouteName());
Modifier::setMetaTags("shortcut icon", Site::logo());