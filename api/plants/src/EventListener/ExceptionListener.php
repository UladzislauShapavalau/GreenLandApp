<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

final class ExceptionListener
{
    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        switch (true) {
            case $exception instanceof UnprocessableEntityHttpException:
                $code = Response::HTTP_UNPROCESSABLE_ENTITY;
                $error = explode(PHP_EOL, $exception->getMessage())[0];
                break;
            case $exception instanceof HttpExceptionInterface:
                $code = $exception->getStatusCode();
                $error = $exception->getMessage();
                break;
            default:
                $code = $exception->getCode() ?: Response::HTTP_BAD_REQUEST;
                $error = $exception->getMessage();
        }
        $data = [
            'code' => $code,
            'message' => $error ?: 'Fatal Error',
        ];
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        $event->setResponse(new JsonResponse($json, $code, [], true));
    }
}