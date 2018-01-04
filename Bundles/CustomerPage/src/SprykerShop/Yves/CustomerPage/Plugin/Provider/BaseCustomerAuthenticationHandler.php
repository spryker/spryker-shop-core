<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\Provider;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\HomePage\Plugin\Provider\HomePageControllerProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Client\Customer\CustomerClientInterface getClient()
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageConfig getConfig()
 */
class BaseCustomerAuthenticationHandler extends AbstractPlugin
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function createRefererRedirectResponse(Request $request)
    {
        $targetUrl = $this->filterUrl(
            $request->headers->get('Referer'),
            $this->getConfig()->getYvesHost(),
            $this->getHomeUrl()
        );

        $response = $this->getFactory()->createRedirectResponse($targetUrl);

        return $response;
    }

    /**
     * @param string|null $refererUrl
     * @param string $allowedHost
     * @param string $fallbackUrl
     *
     * @return null|string
     */
    protected function filterUrl($refererUrl, $allowedHost, $fallbackUrl)
    {
        if ($refererUrl === null) {
            return $fallbackUrl;
        }

        $allowedUrl = sprintf('#^(?P<scheme>http|https)://%s/(?P<uri>.*)$#', $allowedHost);
        $isRefererUrlAllowed = (bool)preg_match($allowedUrl, $refererUrl, $matches);
        if ($isRefererUrlAllowed) {
            return sprintf('%s://%s/%s', $matches['scheme'], $allowedHost, $matches['uri']);
        }

        return $fallbackUrl;
    }

    /**
     * @return string
     */
    protected function getHomeUrl()
    {
        return $this->getFactory()->getApplication()->url(HomePageControllerProvider::ROUTE_HOME);
    }
}
