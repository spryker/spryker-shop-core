<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Generator;

use Generated\Shared\Transfer\ExpressCheckoutRedirectUrlsTransfer;
use SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetConfig;
use Symfony\Cmf\Component\Routing\ChainRouterInterface;

class ExpressCheckoutRedirectGenerator implements ExpressCheckoutRedirectGeneratorInterface
{
    /**
     * @var \Symfony\Cmf\Component\Routing\ChainRouterInterface
     */
    protected ChainRouterInterface $router;

    /**
     * @var \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetConfig
     */
    protected PaymentAppWidgetConfig $config;

    /**
     * @param \Symfony\Cmf\Component\Routing\ChainRouterInterface $router
     * @param \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetConfig $config
     */
    public function __construct(ChainRouterInterface $router, PaymentAppWidgetConfig $config)
    {
        $this->router = $router;
        $this->config = $config;
    }

    /**
     * @return \Generated\Shared\Transfer\ExpressCheckoutRedirectUrlsTransfer
     */
    public function generateRedirectUrls(): ExpressCheckoutRedirectUrlsTransfer
    {
        $redirectUrlsTransfer = new ExpressCheckoutRedirectUrlsTransfer();

        $redirectUrlsTransfer->setSuccessUrl(
            $this->router->generate($this->config->getExpressCheckoutSuccessRouteName(), [], ChainRouterInterface::ABSOLUTE_URL),
        );

        $redirectUrlsTransfer->setFailureUrl(
            $this->router->generate($this->config->getExpressCheckoutFailureRouteName(), [], ChainRouterInterface::ABSOLUTE_URL),
        );

        $redirectUrlsTransfer->setCancelUrl(
            $this->router->generate($this->config->getExpressCheckoutCancelRouteName(), [], ChainRouterInterface::ABSOLUTE_URL),
        );

        $redirectUrlsTransfer->setPreOrderUrl(
            $this->router->generate($this->config->getExpressCheckoutPreOrderRouteName(), [], ChainRouterInterface::ABSOLUTE_URL),
        );

        return $redirectUrlsTransfer;
    }
}
