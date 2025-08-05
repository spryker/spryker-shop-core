<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\StorageRouter\UrlGenerator;

use SprykerShop\Yves\StorageRouter\Dependency\Client\StorageRouterToUrlStorageClientInterface;
use SprykerShop\Yves\StorageRouter\ParameterMerger\ParameterMergerInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;

class StorageUrlGenerator implements UrlGeneratorInterface
{
    /**
     * @var string
     */
    protected const REQUEST_CONTEXT_STORE = 'store';

    /**
     * @var string
     */
    protected const REQUEST_CONTEXT_LOCALE = '_locale';

    /**
     * @var string
     */
    protected const HOME_PAGE_URL_PATTERN = '/%s/';

    /**
     * @var \Symfony\Component\Routing\RequestContext
     */
    protected $context;

    /**
     * @var \SprykerShop\Yves\StorageRouter\Dependency\Client\StorageRouterToUrlStorageClientInterface
     */
    protected $urlStorageClient;

    /**
     * @var \SprykerShop\Yves\StorageRouter\ParameterMerger\ParameterMergerInterface
     */
    protected $parameterMerger;

    /**
     * @var array<\SprykerShop\Yves\StorageRouterExtension\Dependency\Plugin\StorageRouterEnhancerPluginInterface>
     */
    protected array $storageRouterEnhancerPlugins;

    /**
     * @param \SprykerShop\Yves\StorageRouter\Dependency\Client\StorageRouterToUrlStorageClientInterface $urlStorageClient
     * @param \SprykerShop\Yves\StorageRouter\ParameterMerger\ParameterMergerInterface $parameterMerger
     * @param array<\SprykerShop\Yves\StorageRouterExtension\Dependency\Plugin\StorageRouterEnhancerPluginInterface> $storageRouterEnhancerPlugins
     */
    public function __construct(
        StorageRouterToUrlStorageClientInterface $urlStorageClient,
        ParameterMergerInterface $parameterMerger,
        array $storageRouterEnhancerPlugins
    ) {
        $this->urlStorageClient = $urlStorageClient;
        $this->parameterMerger = $parameterMerger;
        $this->storageRouterEnhancerPlugins = $storageRouterEnhancerPlugins;
    }

    /**
     * @param \Symfony\Component\Routing\RequestContext $context
     *
     * @return void
     */
    public function setContext(RequestContext $context): void
    {
        $this->context = $context;
    }

    /**
     * @return \Symfony\Component\Routing\RequestContext
     */
    public function getContext(): RequestContext
    {
        return $this->context;
    }

    /**
     * @param string $name
     * @param array<int|string, mixed> $parameters
     * @param int $referenceType
     *
     * @return string
     */
    public function generate(string $name, array $parameters = [], int $referenceType = self::ABSOLUTE_PATH): string
    {
        $localeName = $this->getContext()->getParameter(static::REQUEST_CONTEXT_LOCALE);
        foreach ($this->storageRouterEnhancerPlugins as $storageRouterEnhancerPlugin) {
            $name = $storageRouterEnhancerPlugin->beforeMatch($name, $this->getContext());
        }
        if (!$this->urlStorageClient->matchUrl($name, $localeName)) {
            return $this->generateHomePageUrl($referenceType);
        }

        foreach ($this->storageRouterEnhancerPlugins as $storageRouterEnhancerPlugin) {
            $storageRouterEnhancerPlugin->afterMatch([], $this->getContext());
        }

        parse_str($this->getContext()->getQueryString(), $requestParameter);

        $queryString = http_build_query($this->parameterMerger->mergeParameters($requestParameter, $parameters));

        if ($queryString) {
            $name .= '?' . $queryString;
        }

        $url = $this->getUrlOrPathForType($name, $referenceType);

        foreach (array_reverse($this->storageRouterEnhancerPlugins) as $storageRouterEnhancerPlugin) {
            $url = $storageRouterEnhancerPlugin->afterGenerate($url, $this->getContext(), $referenceType);
        }

        return $url;
    }

    /**
     * @param array<string, mixed> $parameters
     *
     * @return string
     */
    protected function buildQueryString(array $parameters): string
    {
        parse_str($this->getContext()->getQueryString(), $requestParameter);
        $mergedQueryParameter = $this->parameterMerger->mergeParameters($requestParameter, $parameters);
        if (count($mergedQueryParameter) > 0) {
            return sprintf('?%s', http_build_query($mergedQueryParameter));
        }

        return '';
    }

    /**
     * @param string $pathInfo
     * @param int $referenceType
     *
     * @return string
     */
    protected function getUrlOrPathForType($pathInfo, $referenceType)
    {
        $url = $pathInfo;

        switch ($referenceType) {
            case static::ABSOLUTE_URL:
            case static::NETWORK_PATH:
                $url = $this->buildUrl($pathInfo, $referenceType);

                break;
            case static::ABSOLUTE_PATH:
                $url = $pathInfo;

                break;
            case static::RELATIVE_PATH:
                $url = UrlGenerator::getRelativePath($this->context->getPathInfo(), $pathInfo);

                break;
        }

        return $url;
    }

    /**
     * @param string $pathInfo
     * @param int $referenceType
     *
     * @return string
     */
    protected function buildUrl(string $pathInfo, int $referenceType): string
    {
        $scheme = $this->getScheme();
        $port = $this->getPortPart($scheme);
        $schemeAuthority = ($referenceType === static::NETWORK_PATH) ? '//' : "$scheme://";
        $schemeAuthority .= $this->context->getHost() . $port;

        return $schemeAuthority . $this->context->getBaseUrl() . $pathInfo;
    }

    /**
     * @param string $scheme
     *
     * @return string
     */
    protected function getPortPart(string $scheme): string
    {
        if ($scheme === 'http' && $this->context->getHttpPort() !== 80) {
            return ':' . $this->context->getHttpPort();
        }

        if ($scheme === 'https' && $this->context->getHttpsPort() !== 443) {
            return ':' . $this->context->getHttpsPort();
        }

        return '';
    }

    /**
     * @return string
     */
    protected function getScheme(): string
    {
        return $this->context->getScheme();
    }

    /**
     * @param int $referenceType
     *
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException
     *
     * @return string
     */
    protected function generateHomePageUrl(int $referenceType): string
    {
        $context = $this->getContext();
        $store = $context->getParameter(static::REQUEST_CONTEXT_STORE);

        if (!$store) {
            throw new RouteNotFoundException();
        }

        $homeUrl = sprintf(static::HOME_PAGE_URL_PATTERN, $store);

        return $this->getUrlOrPathForType($homeUrl, $referenceType);
    }
}
