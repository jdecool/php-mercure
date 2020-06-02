<?php

declare(strict_types=1);

namespace JDecool\Mercure;

use Http\Client\Common\HttpMethodsClientInterface;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

final class Publisher implements PublisherInterface
{
    private $hubUrl;
    private $jwtProvider;
    private $httpClient;

    /**
     * @param callable(Update $update): string $jwtProvider
     */
    public function __construct(string $hubUrl, callable $jwtProvider, HttpMethodsClientInterface $httpClient)
    {
        $this->hubUrl = $hubUrl;
        $this->jwtProvider = $jwtProvider;
        $this->httpClient = $httpClient;
    }

    public function __invoke(Update $update): ResponseInterface
    {
        $postData = [
            'topic' => $update->getTopics(),
            'data' => $update->getData(),
            'private' => $update->isPrivate() ? 'on' : null,
            'id' => $update->getId(),
            'type' => $update->getType(),
            'retry' => $update->getRetry(),
        ];

        $jwt = ($this->jwtProvider)($update);
        $this->validateJwt($jwt);

        return $this->httpClient->post(
            $this->hubUrl,
            ['Authorization' => "Bearer $jwt"],
            $this->buildQuery($postData)
        );
    }

    private function buildQuery(array $data): string
    {
        $parts = [];
        foreach ($data as $key => $value) {
            if (null === $value) {
                continue;
            }

            if (\is_array($value)) {
                foreach ($value as $v) {
                    $parts[] = $this->encode($key, $v);
                }

                continue;
            }

            $parts[] = $this->encode($key, $value);
        }

        return implode('&', $parts);
    }

    private function encode($key, $value): string
    {
        return sprintf('%s=%s', $key, urlencode((string) $value));
    }

    private function validateJwt(string $jwt): void
    {
        if (!preg_match('/^[A-Za-z0-9-_]+\.[A-Za-z0-9-_]+\.[A-Za-z0-9-_]*$/', $jwt)) {
            throw new InvalidArgumentException('The provided JWT is not valid');
        }
    }
}
