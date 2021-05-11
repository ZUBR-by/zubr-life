<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DisplayWidget extends AbstractController
{
    public function __invoke(Request $request) : Response
    {
        $response = new RedirectResponse(
            'https://new.zubr.life',
            Response::HTTP_FOUND,
            ['Access-Control-Allow-Origin' => '*']
        );
        $response->headers->setCookie(
            new Cookie(
                'token',
                json_encode($request->query->all()),
                36000,
                '/',
                '.zubr.life'
            )
        );

        return $response;
    }
}
