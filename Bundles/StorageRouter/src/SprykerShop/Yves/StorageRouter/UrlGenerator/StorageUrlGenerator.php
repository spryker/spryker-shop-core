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
     * @param \SprykerShop\Yves\StorageRouter\Dependency\Client\StorageRouterToUrlStorageClientInterface $urlStorageClient
     * @param \SprykerShop\Yves\StorageRouter\ParameterMerger\ParameterMergerInterface $parameterMerger
     */
    public function __construct(StorageRouterToUrlStorageClientInterface $urlStorageClient, ParameterMergerInterface $parameterMerger)
    {
        $this->urlStorageClient = $urlStorageClient;
        $this->parameterMerger = $parameterMerger;
    }

    /**
     * @param \Symfony\Component\Routing\RequestContext $context
     *
     * @return void
     */
    public function setContext(RequestContext $context)
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
     * @param array<string, mixed> $parameters
     * @param int $referenceType
     *
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException
     *
     * @return string
     */
    public function generate(string $name, array $parameters = [], int $referenceType = self::ABSOLUTE_PATH): string
    {
        $localeName = $this->getContext()->getParameter('_locale');
        if (!$this->urlStorageClient->matchUrl($name, $localeName)) {
            throw new RouteNotFoundException();
        }

        parse_str($this->getContext()->getQueryString(), $requestParameter);

        $queryString = http_build_query($this->parameterMerger->mergeParameters($requestParameter, $parameters));

        if ($queryString) {
            $name .= '?' . $queryString;
        }

        return $this->getUrlOrPathForType($name, $referenceType);
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
}
