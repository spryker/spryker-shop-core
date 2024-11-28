<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\StorageRouter\RequestMatcher;

use SprykerShop\Yves\StorageRouter\Dependency\Client\StorageRouterToUrlStorageClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Routing\RequestContext;

class StorageRequestMatcher implements RequestMatcherInterface
{
    /**
     * @var string
     */
    protected const ATTRIBUTE_PATH_INFO = 'pathinfo';

    /**
     * @var \SprykerShop\Yves\StorageRouter\Dependency\Client\StorageRouterToUrlStorageClientInterface
     */
    protected $urlStorageClient;

    /**
     * @var array<\SprykerShop\Yves\StorageRouterExtension\Dependency\Plugin\StorageRouterEnhancerPluginInterface>
     */
    protected array $storageRouterEnhancerPlugins;

    /**
     * @param \SprykerShop\Yves\StorageRouter\Dependency\Client\StorageRouterToUrlStorageClientInterface $urlStorageClient
     * @param array<\SprykerShop\Yves\StorageRouterExtension\Dependency\Plugin\StorageRouterEnhancerPluginInterface> $storageRouterEnhancerPlugins
     */
    public function __construct(
        StorageRouterToUrlStorageClientInterface $urlStorageClient,
        array $storageRouterEnhancerPlugins
    ) {
        $this->urlStorageClient = $urlStorageClient;
        $this->storageRouterEnhancerPlugins = $storageRouterEnhancerPlugins;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\Routing\Exception\ResourceNotFoundException
     *
     * @return array<string, mixed>
     */
    public function matchRequest(Request $request): array
    {
        $requestContext = new RequestContext();
        $pathinfo = $request->getPathInfo();

        foreach ($this->storageRouterEnhancerPlugins as $storageRouterEnhancerPlugin) {
            $pathinfo = $storageRouterEnhancerPlugin->beforeMatch($pathinfo, $requestContext);
        }

        if ($pathinfo !== '/') {
            $localeName = $request->attributes->get('_locale');
            $urlDetails = $this->urlStorageClient->matchUrl($pathinfo, $localeName);

            if ($urlDetails) {
                foreach ($this->storageRouterEnhancerPlugins as $storageRouterEnhancerPlugin) {
                    $urlDetails = $storageRouterEnhancerPlugin->afterMatch($urlDetails, $requestContext);
                }
                $urlDetails[static::ATTRIBUTE_PATH_INFO] = $pathinfo;

                return $urlDetails;
            }
        }

        throw new ResourceNotFoundException();
    }
}
