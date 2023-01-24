<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopRouter\Plugin\Router;

use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

/**
 * @deprecated Use `spryker-shop/storage-router` instead.
 *
 * @method \SprykerShop\Yves\ShopRouter\ShopRouterFactory getFactory()
 */
class StorageRouter extends AbstractRouter
{
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
        $urlMatcher = $this->getFactory()->getUrlMatcher();
        $localeName = $this->getLocale();

        if (!$urlMatcher->matchUrl($name, $localeName)) {
            $name = $this->getDefaultLocalePrefix() . $name;

            if (!$urlMatcher->matchUrl($name, $localeName)) {
                throw new RouteNotFoundException();
            }
        }

        $requestParameters = $this->getRequest()->query->all();

        $mergedParameters = $this
            ->getFactory()
            ->createParameterMerger()
            ->mergeParameters($requestParameters, $parameters);

        $pathInfo = $this
            ->getFactory()
            ->createUrlMapper()
            ->generateUrlFromParameters($mergedParameters);

        $pathInfo = $name . $pathInfo;

        return $this->getUrlOrPathForType($pathInfo, $referenceType);
    }

    /**
     * @inheritDoc
     *
     * @throws \Symfony\Component\Routing\Exception\ResourceNotFoundException
     */
    public function match(string $pathinfo): array
    {
        $defaultLocalePrefix = $this->getDefaultLocalePrefix();

        if ($defaultLocalePrefix === $pathinfo || $defaultLocalePrefix . '/' === $pathinfo) {
            throw new ResourceNotFoundException();
        }

        if ($pathinfo !== '/') {
            $urlMatcher = $this->getFactory()->getUrlMatcher();
            $localeName = $this->getLocale();

            $urlDetails = $urlMatcher->matchUrl($pathinfo, $localeName);

            if (!$urlDetails) {
                $urlDetails = $urlMatcher->matchUrl($defaultLocalePrefix . $pathinfo, $localeName);
            }

            if ($urlDetails) {
                $resourceCreatorResult = $this->getFactory()
                    ->createResourceCreatorHandler()
                    ->create($urlDetails['type'], $urlDetails['data']);

                if ($resourceCreatorResult) {
                    return $resourceCreatorResult;
                }
            }
        }

        throw new ResourceNotFoundException();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest()
    {
        $application = $this->getContainer();
        /** @var \Symfony\Component\HttpFoundation\RequestStack|null $requestStack */
        $requestStack = $application->get('request_stack');
        $request = $requestStack ? $requestStack->getCurrentRequest() : $application->get('request');

        return $request;
    }

    /**
     * @return \Silex\Application
     */
    protected function getApplication()
    {
        return $this->getFactory()->getApplication();
    }

    /**
     * @return string
     */
    protected function getDefaultLocalePrefix()
    {
        return '/' . mb_substr($this->getLocale(), 0, 2);
    }
}
