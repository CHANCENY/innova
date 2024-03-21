<?php

namespace Innova\Types;

class ContentTypeAlreadyExistException extends \Exception
{
    protected $message = "Content type already exist";

    protected $code = 9097;
}