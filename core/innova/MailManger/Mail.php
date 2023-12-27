<?php

namespace Innova\MailManger;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mail {

  private BodyBuilder $body;
  private Headers $headers;

  public function __construct(
    private readonly Reciever $reciever,
    private readonly array    $params,
    private readonly BCC $bcc = new BCC([]),
    private readonly Attachments $attachements = new Attachments([])
  ) {
    $this->body = new BodyBuilder($this->params);
    $this->headers = new Headers();
  }

  public function processMail(): bool
  {
    $mail = new PHPMailer(true);

    try {
      //Server settings
      $mail->SMTPDebug = $this->headers->getSMTPDebug();
      $mail->isSMTP();
      $mail->Host       = $this->headers->getMailHost();
      $mail->SMTPAuth   = $this->headers->isSMTPAuth();
      $mail->Username   = $this->headers->getUsername();
      $mail->Password   = $this->headers->getPassword();
      $mail->SMTPSecure = $this->headers->getSMTPSecure();
      $mail->Port       = $this->headers->getPort();
      $mail->setFrom($this->headers->getUsername(), $this->headers->getName());
      //Recipients
      $recieverAddresses = $this->reciever->getRecieverAddress();
      $recieverNames = $this->reciever->getRecieverNames();

      foreach ($recieverAddresses as $key => $receiverAddress) {
        $mail->addAddress($receiverAddress, $recieverNames[$key] ?? "");
      }

      if($this->headers->reply)
      {
        $mail->addReplyTo($this->headers->getReplyTo());
      }

      if($this->bcc->bcc){
        $bccAddresses = $this->bcc->getBccAddresses();
        foreach ($bccAddresses as $bccAddress)
        {
          $mail->addCC($bccAddress);
        }
      }

      //Attachments
      if($this->attachements->isAttachment())
      {
        $files = $this->attachements->getAttachments();
        foreach ($files as $file)
        {
          $list = explode('/', $file);
          $name = end($list);
          $mail->addAttachment($file, $name);    //Optional name
        }
      }
      //Content
      $mail->isHTML(true);                                  //Set email format to HTML
      $mail->Subject = $this->body->getSubject();
      $mail->Body    = $this->body->getMessage();
      $mail->AltBody = $this->body->getParams()['altbody'] ?? "";

      return $mail->send();
    } catch (Exception $e) {
      return false;
    }
  }

  public static function send(
    Reciever    $receiver, array $params,
    BCC         $bcc = new BCC([]),
    Attachments $attachments = new Attachments([])): bool
  {
    return (new static($receiver,$params,$bcc,$attachments))->processMail();
  }
}