<?php

namespace Innova\interfaces;

interface TwoAuthenticationInterface {

  /**
   * Setting Transmission method.
   *
   * @param string $transmission Can be email, sms, app
   *
   * @return void
   */
  public function setTransmission(string $transmission): void;

  /**
   * @param string $receiver This can be email address, phone number
   *
   * @return void
   */
  public function setOtpReceiver(string $receiver): void;

  /**
   * Verification that give $receiver really is in our system.
   * @return void
   */
  public function verifyReceiver(): void;

  /**
   * Sending otp or code.
   * @return bool
   */
  public function sendOtp(): bool;

  /**
   * Landing call controller.
   * @param string $controllerId Controller ID.
   *
   * @return void
   */
  public function setCallback(string $controllerId): void;
}