<?php


namespace App\Controller;

use OpenApi\Annotations as OA;
use Symfony\Component\Routing\Annotation\Route;

class DocController
{
    /**
     * BileMo api documentation in json
     *
     * @Route  ("/doc.json",name="doc_json",methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="OK",
     * )
     *
     * @OA\Tag (name="Doc")
     */


}