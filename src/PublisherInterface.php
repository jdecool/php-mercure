<?php

declare(strict_types=1);

namespace JDecool\Mercure;

use Psr\Http\Message\ResponseInterface;

interface PublisherInterface
{
    public function __invoke(Update $update): ResponseInterface;
}
