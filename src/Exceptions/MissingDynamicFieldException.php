<?php

namespace Ferdinandbr\LaravelModelUniqueField\Exceptions;

use Exception;

class MissingDynamicFieldException extends Exception
{
    protected $message = 'The dynamic field is not set in the model.';

    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        parent::__construct($message ?? $this->message, $code, $previous);
    }
}
