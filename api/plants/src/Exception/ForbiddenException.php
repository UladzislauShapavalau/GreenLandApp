<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class ForbiddenException extends AppException
{
    /**
     * @var int
     */
    protected $code = Response::HTTP_FORBIDDEN;
}
