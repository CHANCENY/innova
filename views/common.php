<?php

/**
 *@file
 * Common.php file
 */

/**
 * Common file is php file that will execute at every end of request
 * You can put you common code here.
 *
 * Note As this file execute don't put heavy resource usage code or code
 * that will take more time execute.
 */

//Active controller
use Innova\Modifier\Modifier;

/**
 * Above code will set title of your page based on controller route active
 * and will get Site logo for tab icon
 *
 * Continue your code below.
 */

Modifier::addMenu("d458f154-8d9d-4715-b719-7b4fb28f7de2",['class'=>'fa fa-gear'],'First Group', "fa fa-dropbox");
Modifier::addMenu("68341807-71a9-4ff1-b7ab-d50c692586b2", ['class'=>'fa fa-gear'],'First Group','fa fa-dropbox');
Modifier::addMenu("ghghdtuhdhfhghfhdhdhh");