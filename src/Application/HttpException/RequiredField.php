<?php

namespace App\Application\Ex;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RequiredField extends HttpException
{
    public function __construct(string $filed)
    {

        parent::__construct(

            Response::HTTP_BAD_REQUEST,
            sprintf('Required field: %s', $filed),

        );
    }
}
