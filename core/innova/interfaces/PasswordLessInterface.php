<?php

namespace Innova\interfaces;

interface PasswordLessInterface {

  /**
   * Set the Email address of user.
   * @param string $emailAddress
   * Email Address of user attempting to log in
   *
   * @return void
   * Return nothing.
   */
  public function setUserEmailAddress(string $emailAddress): void;

  /**
   * Controller id which will have post-method allowed to accept incoming submit
   * of login for on this controller, you can either prompt use to enter token sent,
   * or if you are not using tokens, you can prompt and suitable message.
   *
   * @param string $controllerId Id of the controller to handle the submit of
   * login form
   *
   * @return void
   * Returns nothing.
   */
  public function setCallBackController(string $controllerId): void;

  /**
   * This method need to be run after setting email and callback.
   * @return void
   */
  public function executeAuthentication(): void;

  /**
   * Sending the generated token or one time link.
   * @return bool
   * Returns true if mail sent false if fails.
   */
  public function sendAuthenticationToken(): bool;
}