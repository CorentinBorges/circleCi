<?php

namespace App\Responder\ExceptionResponder;

use App\Responder\JsonResponder;
use Symfony\Component\HttpFoundation\Response;

class EntityNotFoundResponder
{
    public static function build($message, $details)
    {
        return JsonResponder::responder(
            json_encode([
                "error" => [
                    'Message' => $message,
                    'details' => $details]]),
            Response::HTTP_NOT_FOUND
        );
    }
}