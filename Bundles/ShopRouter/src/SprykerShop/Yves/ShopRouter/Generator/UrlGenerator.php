<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopRouter\Generator;

use Pimple;
use Psr\Log\LoggerInterface;
use Spryker\Service\UtilText\Model\Url\Url;
use Symfony\Component\Routing\CompiledRoute;
use Symfony\Component\Routing\Generator\UrlGenerator as SymfonyUrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * @deprecated Use `spryker/router` instead.
 */
class UrlGenerator extends SymfonyUrlGenerator
{
    /**
     * @var string
     */
    public const HOME = 'home';

    /**
     * @var string
     */
    public const ERROR_PATH = '/error/404';

    /**
     * @var string
     */
    protected const ROUTE_NOT_FOUND_URL = '';

    /**
     * @var \Pimple
     */
    protected $app;

    /**
     * @var \Symfony\Component\Routing\RouteCollection
     */
    protected $routes;

    /**
     * @var \Symfony\Component\Routing\RequestContext
     */
    protected $context;

    /**
     * @param \Pimple $app
     * @param \Symfony\Component\Routing\RouteCollection $routes
     * @param \Symfony\Component\Routing\RequestContext $context
     * @param \Psr\Log\LoggerInterface|null $logger
     */
    public function __construct(Pimple $app, RouteCollection $routes, RequestContext $context, ?LoggerInterface $logger = null)
    {
        parent::__construct($routes, $context, $logger);

        $this->app = $app;
    }

    /**
     * @param string $name
     * @param array<string, mixed> $parameters
     * @param int $referenceType
     *
     * @return string
     */
    public function generate(string $name, array $parameters = [], int $referenceType = self::ABSOLUTE_PATH): string
    {
        $route = $this->routes->get($name);
        if ($route === null) {
            return static::ROUTE_NOT_FOUND_URL;
        }

        $compiledRoute = $route->compile();
        $parameters = $this->convertParameters($parameters, $route);

        $url = parent::generate($name, $parameters, $referenceType);

        [$url, $queryParams] = $this->stripQueryParams($url);

        $url = $this->setVariablePath($name, $url, $compiledRoute, $route, $referenceType);
        $url = $this->appendQueryParams($url, $queryParams);

        return $url;
    }

    /**
     * @param array<string, mixed> $parameters
     * @param \Symfony\Component\Routing\Route $route
     *
     * @return array
     */
    protected function convertParameters(array $parameters, Route $route)
    {
        $converters = $route->getOption('_converters');
        foreach ($parameters as $name => $value) {
            if (!isset($converters[$name]) || !isset($parameters[$name])) {
                continue;
            }

            $parameters[$name] = $converters[$name]($value, $this->app['request']);
        }

        return $parameters;
    }

    /**
     * @param string $name
     * @param string $url
     * @param \Symfony\Component\Routing\CompiledRoute $compiledRoute
     * @param \Symfony\Component\Routing\Route $route
     * @param string|int $referenceType
     *
     * @return string
     */
    protected function setVariablePath($name, $url, CompiledRoute $compiledRoute, Route $route, $referenceType)
    {
        if ($compiledRoute->getStaticPrefix() === static::ERROR_PATH) {
            return $url;
        }

        $baseUrl = '/';
        if ($referenceType === static::ABSOLUTE_URL) {
            $baseUrl = $this->generateBaseUrl();
        }

        if ($name !== static::HOME && $baseUrl === $url) {
            $firstPathVariable = current($compiledRoute->getPathVariables());
            $url .= $route->getDefault($firstPathVariable);
        }

        if (!$this->isWebProfilerUrl($url)) {
            $url = $this->setLocalePath($url, $baseUrl, $route);
        }

        return $url;
    }

    /**
     * @return string
     */
    protected function generateBaseUrl()
    {
        $urlBuilder = new Url([
            Url::SCHEME => $this->context->getScheme(),
            Url::HOST => $this->context->getHost(),
            Url::PORT => $this->getPortFromContext(),
        ]);

        return $urlBuilder->build();
    }

    /**
     * @return int
     */
    protected function getPortFromContext()
    {
        if ($this->context->getScheme() === 'https') {
            return $this->context->getHttpsPort();
        }

        return $this->context->getHttpPort();
    }

    /**
     * @param string $url
     * @param string $baseHost
     * @param \Symfony\Component\Routing\Route $route
     *
     * @return string
     */
    protected function setLocalePath($url, $baseHost, Route $route)
    {
        $prefixLocale = mb_substr($this->context->getParameter('_locale'), 0, 2) . '/';
        $localePath = mb_substr($this->context->getPathInfo(), 1, 3);

        if ($prefixLocale === $localePath) {
            $urlToMatch = preg_replace('/^' . preg_quote($baseHost, '/') . '/', $prefixLocale, $url);
            if (preg_match($route->compile()->getRegex(), '/' . $urlToMatch)) {
                return $baseHost . $urlToMatch;
            }
        }

        return $url;
    }

    /**
     * @param string $url
     *
     * @return bool
     */
    protected function isWebProfilerUrl($url)
    {
        if (isset($this->app['profiler.mount_prefix'])) {
            return (bool)preg_match('/^' . preg_quote($this->app['profiler.mount_prefix'], '/') . '/', $url);
        }

        return false;
    }

    /**
     * @param string $url
     *
     * @return array
     */
    protected function stripQueryParams($url)
    {
        $queryParams = parse_url($url, PHP_URL_QUERY);
        $url = strtok($url, '?');

        return [$url, $queryParams];
    }

    /**
     * @param string $url
     * @param string $queryParams
     *
     * @return string
     */
    protected function appendQueryParams($url, $queryParams)
    {
        if ($queryParams) {
            $url .= '?' . $queryParams;
        }

        return $url;
    }
}
