<?php

declare(strict_types=1);

namespace JDecool\Mercure\Tests\Jwt;

use JDecool\Mercure\Jwt\StaticJwtProvider;
use PHPUnit\Framework\TestCase;

class StaticJwtProviderTest extends TestCase
{
    private const JWT = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJtZXJjdXJlLXRlc3QiLCJuYW1lIjoiS8OpdmluIER1bmdsYXMiLCJpYXQiOjE1MTYyMzkwMjJ9.n0KvJ31TCswaK7KuHiN22cLzpjC2UT2rhWqhIDprfmA';

    public function testProvider(): void
    {
        $provider = new StaticJwtProvider(self::JWT);
        $this->assertSame(self::JWT, $provider());
    }
}
