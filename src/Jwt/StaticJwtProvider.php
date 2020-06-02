<?php

declare(strict_types=1);

namespace JDecool\Mercure\Jwt;

final class StaticJwtProvider
{
    private $jwt;

    public function __construct(string $jwt)
    {
        $this->jwt = $jwt;
    }

    public function __invoke(): string
    {
        return $this->jwt;
    }
}
