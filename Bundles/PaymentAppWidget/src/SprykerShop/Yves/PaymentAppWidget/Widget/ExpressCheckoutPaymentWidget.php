<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;
use SprykerShop\Yves\PaymentAppWidget\Plugin\Router\PaymentAppWidgetRouteProviderPlugin;
use Symfony\Cmf\Component\Routing\ChainRouterInterface;

/**
 * @method \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetFactory getFactory()
 */
class ExpressCheckoutPaymentWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_EXPRESS_CHECKOUT_PAYMENT_WIDGET_CONTENT_URL = 'expressCheckoutPaymentWidgetContentUrl';

    public function __construct()
    {
        $this->addExpressCheckoutPaymentWidgetContentUrlParameter();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ExpressCheckoutPaymentWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@PaymentAppWidget/views/express-checkout-payment-widget/express-checkout-payment-widget.twig';
    }

    /**
     * @return void
     */
    protected function addExpressCheckoutPaymentWidgetContentUrlParameter(): void
    {
        $expressCheckoutPaymentWidgetContentUrl = $this->getFactory()
            ->getRouter()
            ->generate(PaymentAppWidgetRouteProviderPlugin::ROUTE_NAME_EXPRESS_CHECKOUT_PAYMENT_WIDGET_CONTENT, [], ChainRouterInterface::ABSOLUTE_URL);

        $this->addParameter(static::PARAMETER_EXPRESS_CHECKOUT_PAYMENT_WIDGET_CONTENT_URL, $expressCheckoutPaymentWidgetContentUrl);
    }
}
