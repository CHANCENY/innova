<?php

//Build up receiver object
$reciever = new \Innova\MailManger\Reciever(
  ['nyasuluchance6@gmail.com'],
  ['Chance Nyasulu', 'Lincoln Nyirenda']
);

//Build up a BCC object
$bcc = new \Innova\MailManger\BCC(['sahlgeorge90@gmail.com']);

//Build up Attachments
$attachment = new \Innova\MailManger\Attachments(['1.pdf']);

//Build up params
$params = [
  'subject' => "Hello Test Mailer",
  'message' => "This is test mail please ignore all content thank you."
];

//Sending Mail
\Innova\MailManger\Mail::send($reciever, $params,$bcc,$attachment);

