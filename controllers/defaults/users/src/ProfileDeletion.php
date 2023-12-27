<?php
namespace Innova\Controller\routers\users\src;

use Innova\Entities\User;
use Innova\modules\Messager;
use Innova\request\Request;
use Innova\Sessions\Session;

class ProfileDeletion extends Request
{
    public function page(): string
    {
        if($this->get('uid', false) && empty(Session::get('visited')))
        {
            Session::set('visited', true);
            $firstname = User::load($this->get('uid'))->firstname();
            return "<div class='page-wrapper'>
    <div class='content'>
        <div class='row'><div class='col-md-6 offset-md-3'>
        <h1>Deletion $firstname</h1>
                   <p>Are you sure you want to delete this user permanently ?</p>
                   <p>
                   <a class='btn btn-danger text-decoration-none' href='{$this->httpSchema()}/users/delete/{$this->get('uid')}'>Yes</a>
                  <a class='btn btn-primary text-decoration-none' href='{$this->httpSchema()}/user/{$this->get('uid')}'>No</a>
                   </p>
                   </div>
                   </div>
                   </div>
                   </div>";
        }
        else{
            $user = User::load($this->get('uid'));
            if(!empty($user->id()))
            {
                if($user->delete())
                {
                    Session::clear('visited');
                    Messager::message()->addMessage("Deleted profile of {$user->firstname()} successfully");
                    $this->redirection('/');
                }
            }
        }
        Session::clear('visited');
        return "Encounter Error user not found";
    }
}