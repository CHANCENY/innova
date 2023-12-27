<?php

use Innova\Modifier\Modifier;

function users_head_sections(): void
{
    $controller = active_controller();
    $definition = $controller->getController();

    Modifier::setTitle($controller->getRouteName(),true);

    if(!empty($definition['id']) &&  $definition['id'] === "ghgfhehrhrlgjgghfhdh")
    {
        Modifier::setTitle("User {$_GET['user_id']}", true);

    }

    if(!empty($definition['id']) &&  $definition['id'] === "yhgfhfdhgfhghfghdhdhdhshhshsh")
    {
        Modifier::setTitle("Create User", true);
    }

    if(!empty($definition['id']) &&  $definition['id'] === "e0c1b1a6-97ee-4e21-8a1b-3a675422218d")
    {
        $id = (new \Innova\request\Request())->get("uid");
        $user = \Innova\Entities\User::load($id);
        Modifier::setTitle("Edit ". $user->firstname() . ' ' . $user->lastname(), true);
        Modifier::setMetaTags("description", $user->overview());
    }
}