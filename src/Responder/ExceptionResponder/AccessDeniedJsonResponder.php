<?php

namespace App\Responder\ExceptionResponder;

use App\Responder\JsonResponder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AccessDeniedJsonResponder
{
    public static function build($message)
    {
        return JsonResponder::responder(
            json_encode([
                "error" => [
                    'Message' => "Access denied",
                    'details' => $message]]),
            Response::HTTP_FORBIDDEN
        );
    }
}
