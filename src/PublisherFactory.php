<?php

declare(strict_types=1);

namespace JDecool\Mercure;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClientFactory;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\RequestFactory;

class PublisherFactory
{
    private $httpClient;
    private $requestFactory;
    private $plugins;

    public function __construct(HttpClient $httpClient = null, RequestFactory $requestFactory = null)
    {
        $this->httpClient = $httpClient ?? HttpClientDiscovery::find();
        $this->requestFactory = $requestFactory ?? MessageFactoryDiscovery::find();
        $this->plugins = [];
    }

    /**
     * @param callable(Update $update): string $jwtProvider
     */
    public function create(string $hubUrl, callable $jwtProvider): Publisher
    {
        $this->plugins[] = new Plugin\HeaderDefaultsPlugin([
            'User-Agent' => 'PHP-Mercure-Client',
        ]);

        $httpClient = new HttpMethodsClient(
            (new PluginClientFactory())->createClient($this->httpClient, $this->plugins),
            $this->requestFactory
        );

        return new Publisher($hubUrl, $jwtProvider, $httpClient);
    }

    public function addPlugin(Plugin $plugin): void
    {
        $this->plugins[] = $plugin;
    }
}
