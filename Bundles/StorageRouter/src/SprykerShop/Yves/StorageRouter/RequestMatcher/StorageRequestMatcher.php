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

class StorageRequestMatcher implements RequestMatcherInterface
{
    /**
     * @var \SprykerShop\Yves\StorageRouter\Dependency\Client\StorageRouterToUrlStorageClientInterface
     */
    protected $urlStorageClient;

    /**
     * @param \SprykerShop\Yves\StorageRouter\Dependency\Client\StorageRouterToUrlStorageClientInterface $urlStorageClient
     */
    public function __construct(StorageRouterToUrlStorageClientInterface $urlStorageClient)
    {
        $this->urlStorageClient = $urlStorageClient;
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
        $pathinfo = $request->getPathInfo();
        if ($pathinfo !== '/') {
            $localeName = $request->attributes->get('_locale');
            $urlDetails = $this->urlStorageClient->matchUrl($pathinfo, $localeName);

            if ($urlDetails) {
                return $urlDetails;
            }
        }

        throw new ResourceNotFoundException();
    }
}
