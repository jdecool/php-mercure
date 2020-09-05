<?php

declare(strict_types=1);

namespace JDecool\Mercure\Tests;

use Http\Client\Common\HttpMethodsClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\Strategy\MockClientStrategy;
use Http\Mock\Client;
use JDecool\Mercure\Publisher;
use JDecool\Mercure\Update;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class PublisherTest extends TestCase
{
    private const URL = 'https://demo.mercure.rocks/.well-known/mercure';
    private const JWT = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtZXJjdXJlIjp7InN1YnNjcmliZSI6WyIqIl0sInB1Ymxpc2giOlsiKiJdfX0.M1yJUov4a6oLrigTqBZQO_ohWUsg3Uz1bnLD4MIyWLo';
    private const AUTH_HEADER = 'Bearer '.self::JWT;

    public function setUp(): void
    {
        HttpClientDiscovery::prependStrategy(MockClientStrategy::class);
    }

    public function testPublish(): void
    {
        $jwtProvider = function(): string {
            return self::JWT;
        };

        $client = new Client();
        $httpClient = new HttpMethodsClient($client, MessageFactoryDiscovery::find());

        // Set $httpClient to null to dispatch a real update through the demo hub
        $publisher = new Publisher(self::URL, $jwtProvider, $httpClient);
        $response = $publisher(
            new Update(
                'https://demo.mercure.rocks/demo/books/1.jsonld',
                'Hi from Symfony!',
                true,
                'id',
                null,
                3
            )
        );
        $this->assertInstanceOf(ResponseInterface::class, $response);

        $requests = $client->getRequests();
        $this->assertCount(1, $requests);

        $request = reset($requests);
        $this->assertInstanceOf(RequestInterface::class, $request);
        $this->assertSame('POST', $request->getMethod());
        $this->assertSame(self::URL, (string) $request->getUri());
        $this->assertSame(self::AUTH_HEADER, $request->getHeaderLine('Authorization'));
        $this->assertSame('topic=https%3A%2F%2Fdemo.mercure.rocks%2Fdemo%2Fbooks%2F1.jsonld&data=Hi+from+Symfony%21&private=on&id=id&retry=3', (string) $request->getBody());
    }

    public function testInvalidJwt(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The provided JWT is not valid');

        $jwtProvider = function(): string {
            return "invalid\r\njwt";
        };

        $httpClient = new HttpMethodsClient(
            new Client(),
            MessageFactoryDiscovery::find()
        );

        $publisher = new Publisher(self::URL, $jwtProvider, $httpClient);
        $publisher(new Update('https://demo.mercure.rocks/demo/books/1.jsonld', 'Hi from Symfony!'));
    }
}
