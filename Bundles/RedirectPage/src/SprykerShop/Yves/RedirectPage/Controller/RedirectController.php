<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\RedirectPage\Controller;

use Spryker\Shared\Storage\StorageConstants;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class RedirectController extends AbstractController
{
    public const STORAGE_CACHE_STRATEGY = StorageConstants::STORAGE_CACHE_STRATEGY_INACTIVE;

    protected const HEADER_PARAMETER_CACHE_CONTROL_VALUE = 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0';

    /**
     * @param mixed[] $meta
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectAction($meta)
    {
        $response = $this->redirectResponseExternal(
            $meta['to_url'],
            $meta['status']
        );

        // Fix for browser cache for 301 redirects
        if ($meta['status'] === Response::HTTP_MOVED_PERMANENTLY) {
            $response->headers->set('Cache-Control', static::HEADER_PARAMETER_CACHE_CONTROL_VALUE);

            $expires = date('D, d M Y H:i:s e', strtotime('-1 day'));
            $response->headers->set('Expires', $expires);
        }

        return $response;
    }
}
