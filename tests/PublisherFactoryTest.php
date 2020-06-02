<?php

declare(strict_types=1);

namespace JDecool\Mercure\Tests;

use Http\Client\HttpClient;
use JDecool\Mercure\Publisher;
use JDecool\Mercure\PublisherFactory;
use PHPUnit\Framework\TestCase;

class PublisherFactoryTest extends TestCase
{
    private const URL = 'https://demo.mercure.rocks/.well-known/mercure';
    private const JWT = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtZXJjdXJlIjp7InN1YnNjcmliZSI6WyIqIl0sInB1Ymxpc2giOlsiKiJdfX0.M1yJUov4a6oLrigTqBZQO_ohWUsg3Uz1bnLD4MIyWLo';

    public function createDefaultPublisher(): void
    {
        $jwtProvider = function(): string {
            return self::JWT;
        };

        $factory = new PublisherFactory();
        $publisher = $factory->create(self::URL, $jwtProvider);

        $this->assertInstanceOf(Publisher::class, $publisher);
    }
}
