<?php

namespace Innova\MailManger;

use Innova\Configs\ConfigHandler;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;


class Headers {

    private string $mailHost;
    /**
     * @var true
     */
    private bool $SMTPAuth;
    private string $Username;
    private string $SMTPSecure;
    private string $Password;
    private int $Port;
    private int $SMTPDebug;
    private string $replyTo;
    public bool $reply;
    private $name;

    public function getReplyTo(): string {
        return $this->replyTo;
    }

    public function getMailHost(): string {
        return $this->mailHost;
    }

    public function isSMTPAuth(): bool {
        return $this->SMTPAuth;
    }

    public function getUsername(): string {
        return $this->Username;
    }

    public function getSMTPSecure(): string {
        return $this->SMTPSecure;
    }

    public function getPassword(): string {
        return $this->Password;
    }

    public function getPort(): int {
        return $this->Port;
    }

    public function getSMTPDebug(): int {
        return $this->SMTPDebug;
    }

    public function __construct(string $configName = "") {
        //Get configurations here
        $config = ConfigHandler::config("email_settings");
        if(!empty($config))
        {
            foreach ($config as $value){
                if(isset($value['active']) && $value['active'] === true)
                {
                    $config = $value;
                    break;
                }
            }
        }

        $this->reply = !empty($config['reply']);

        //Server settings
        $this->SMTPDebug = SMTP::DEBUG_OFF;
        $this->mailHost = $config['smtp'] ?? null;
        $this->SMTPAuth = true;
        $this->Username = $config['user'] ?? null;
        $this->Password   = $config['password'] ?? null;
        $this->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->Port  = !empty($config['port']) ? intval($config['port']) : 465;
        $this->name = $config['name'] ?? null;

        if($this->reply === true)
        {
            $this->replyTo = $config['reply'] ?? null;
        }
    }

    public function getName() {
        return $this->name;
    }
}