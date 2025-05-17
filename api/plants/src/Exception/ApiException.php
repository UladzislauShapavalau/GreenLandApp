<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class ApiException extends \Exception
{
    /**
     * @param AppException $previous
     */
    public function __construct(AppException $previous)
    {
        parent::__construct($previous->getMessage(), $previous->getCode() ?: Response::HTTP_BAD_REQUEST, $previous);
    }
}
