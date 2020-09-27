<?php

declare(strict_types=1);

namespace JDecool\Mercure;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClientFactory;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Http\Message\RequestFactory;
use Psr\Http\Message\StreamFactoryInterface;

class PublisherFactory
{
    private $httpClient;
    private $requestFactory;
    private $plugins;

    public function __construct(
        ?HttpClient $httpClient = null,
        ?RequestFactory $requestFactory = null,
        ?StreamFactoryInterface $streamFactory = null
    ) {
        $this->httpClient = $httpClient ?? Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();
        $this->plugins = [];
    }

    /**
     * @param callable(Update $update): string $jwtProvider
     */
    public function create(string $hubUrl, callable $jwtProvider): Publisher
    {
        $this->plugins[] = new Plugin\HeaderDefaultsPlugin([
            'User-Agent' => 'jdecool/php-mercure',
        ]);

        $httpClient = new HttpMethodsClient(
            (new PluginClientFactory())->createClient($this->httpClient, $this->plugins),
            $this->requestFactory,
            $this->streamFactory
        );

        return new Publisher($hubUrl, $jwtProvider, $httpClient);
    }

    public function addPlugin(Plugin $plugin): void
    {
        $this->plugins[] = $plugin;
    }
}
