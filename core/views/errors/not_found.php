<?php

$host = (new \Innova\request\Request())->httpSchema();
$error = <<<ERROR
<div class="error-box">
    <h1>403</h1>
    <h3><i class="fa fa-warning"></i> Oops! Page not found!</h3>
    <p>The page you requested not available.</p>
    <a href="$host" class="btn btn-primary go-home">Go to Home</a>
</div>
ERROR;

echo $error;
