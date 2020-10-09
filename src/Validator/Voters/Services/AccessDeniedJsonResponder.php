<?php


namespace App\Validator\Voters\Services;

use App\Responder\JsonResponder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AccessDeniedJsonResponder
{
    public static function build (AccessDeniedHttpException $exception)
    {
         echo json_encode(array("error"=>[
            'Message'=>"Access denied",
            'details'=>$exception->getMessage()
        ]));
        http_response_code(403);
        header('Content-Type: application/json');
        exit;
    }
}