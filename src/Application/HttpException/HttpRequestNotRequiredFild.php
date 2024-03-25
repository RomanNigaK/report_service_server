<?php

declare(strict_types=1);

namespace App\Application\Exception;


use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


final class HttpRequestNotRequiredFild extends BadRequestHttpException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

}
