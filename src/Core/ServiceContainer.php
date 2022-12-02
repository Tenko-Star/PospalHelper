<?php

namespace PospalHelper\Core;

use GuzzleHttp\Client;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

use PospalHelper\Core\Auth\Sign;
use PospalHelper\Core\Util\Arrays;

use PospalHelper\Core\Provider\ConfigServiceProvider;
use PospalHelper\Core\Provider\HttpServiceProvider;
use PospalHelper\Core\Provider\SignServiceProvider;

/**
 * Base Class ServiceContainer
 *
 * @property Config $config
 * @property Client $http
 * @property Sign $sign
 */
class ServiceContainer extends Container
{
    private const BaseProviders = [
        ConfigServiceProvider::class,
        HttpServiceProvider::class,
        SignServiceProvider::class
    ];

    /** @var ServiceProviderInterface[]  */
    protected array $providers = [];

    protected array $defaultConfig = [
        'http' => [
            'timeout' => 30.0,
        ]
    ];

    protected array $config = [];

    public function __construct(array $config = [], array $prepends = [])
    {
        $this->config = Arrays::extend($this->defaultConfig, $config);

        $this->registerProviders($this->getProviders());

        parent::__construct($prepends);
    }

    public function registerProviders(array $providers): void
    {
        foreach ($providers as $provider) {
            parent::register(new $provider());
        }
    }

    public function getProviders(): array
    {
        return array_merge(self::BaseProviders, $this->providers);
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function __get($name)
    {
        return $this->offsetGet($name);
    }
}