<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerShop\Yves\PaymentAppWidget\Plugin\Router\PaymentAppWidgetRouteProviderPlugin;

class PaymentAppWidgetConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Defines the express checkout configuration strategy for the payment method.
     * - Used to identify payment methods that support express checkout flow.
     *
     * @api
     *
     * @var string
     */
    public const CHECKOUT_CONFIGURATION_STRATEGY_EXPRESS_CHECKOUT = 'express-checkout';

    /**
     * @uses \SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_SUMMARY
     *
     * @var string
     */
    protected const ROUTE_NAME_CHECKOUT_SUMMARY = 'checkout-summary';

    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin::ROUTE_NAME_CART
     *
     * @var string
     */
    protected const ROUTE_NAME_CART = 'cart';

    /**
     * @var string
     */
    protected const EXPRESS_CHECKOUT_PAYMENT_WIDGET_CONTENT_TEMPLATE_PATH = '@PaymentAppWidget/views/express-checkout-payment-widget-content/express-checkout-payment-widget-content.twig';

    /**
     * @var array<string, list<string>>
     */
    protected const SCRIPT_QUERY_STRING_PARAMETERS_BY_APP_PAYMENT_METHOD_KEY = [];

    /**
     * @var array<string, list<string>>
     */
    protected const SCRIPT_PARAMETERS_BY_APP_PAYMENT_METHOD_KEY = [];

    /**
     * @var list<string>
     */
    protected const CHECKOUT_STEPS_TO_SKIP_IN_EXPRESS_CHECKOUT_WORKFLOW = [];

    /**
     * @var list<string>
     */
    protected const QUOTE_FIELDS_TO_CLEAN_IN_EXPRESS_CHECKOUT_WORKFLOW = [];

    /**
     * Specification:
     * - Returns the path to the express checkout payment template.
     * - This template is used to render the express checkout payment methods.
     * - Each express checkout payment method can have its own widget.
     *
     * @api
     *
     * @see \SprykerShop\Yves\PaymentAppWidgetExtension\Dependency\Plugin\ExpressCheckoutPaymentWidgetRenderStrategyPluginInterface for more details.
     *
     * @return string
     */
    public function getExpressCheckoutPaymentWidgetContentTemplatePath(): string
    {
        return static::EXPRESS_CHECKOUT_PAYMENT_WIDGET_CONTENT_TEMPLATE_PATH;
    }

    /**
     * Specification:
     * - Provides the route name for the success page.
     * - This route is used by the success page for handling express checkout payments.
     *
     * @api
     *
     * @return string
     */
    public function getExpressCheckoutSuccessRouteName(): string
    {
        return PaymentAppWidgetRouteProviderPlugin::ROUTE_NAME_EXPRESS_CHECKOUT_SUCCESS;
    }

    /**
     * Specification:
     * - Provides the route name for the failure page.
     * - This route is used by the failure page for handling express checkout payments.
     *
     * @api
     *
     * @return string
     */
    public function getExpressCheckoutFailureRouteName(): string
    {
        return PaymentAppWidgetRouteProviderPlugin::ROUTE_NAME_EXPRESS_CHECKOUT_FAILURE;
    }

    /**
     * Specification:
     * - Provides the route name for the cancel page.
     * - This route is used by the cancel page for handling express checkout payments.
     *
     * @api
     *
     * @return string
     */
    public function getExpressCheckoutCancelRouteName(): string
    {
        return PaymentAppWidgetRouteProviderPlugin::ROUTE_NAME_EXPRESS_CHECKOUT_CANCEL;
    }

    /**
     * Specification:
     * - Provides the route name for the pre-order page.
     * - This route is used by the pre-order payment page for handling express checkout payments.
     *
     * @api
     *
     * @return string
     */
    public function getExpressCheckoutPreOrderRouteName(): string
    {
        return PaymentAppWidgetRouteProviderPlugin::ROUTE_NAME_EXPRESS_CHECKOUT_PRE_ORDER;
    }

    /**
     * Specification:
     *  - Returns the route name to redirect after successful express checkout payment processing.
     *
     * @api
     *
     * @return string
     */
    public function getSuccessActionRedirectRouteName(): string
    {
        return static::ROUTE_NAME_CHECKOUT_SUMMARY;
    }

    /**
     * Specification:
     * - Returns the route name to redirect after canceling express checkout payment.
     *
     * @api
     *
     * @return string|null
     */
    public function getCancelActionRedirectRouteName(): ?string
    {
        return null;
    }

    /**
     * Specification:
     * - Returns the route name to redirect after failed express checkout payment processing.
     *
     * @api
     *
     * @return string|null
     */
    public function getFailureActionRedirectRouteName(): ?string
    {
        return null;
    }

    /**
     * Specification:
     * - Returns the route name for the page where the express checkout workflow starts.
     *
     * @api
     *
     * @return string
     */
    public function getExpressCheckoutStartPageRouteName(): string
    {
        return static::ROUTE_NAME_CART;
    }

    /**
     * Specification:
     * - Returns a map of query string parameters for the Express Checkout widget.
     * - The key represents the app-specific payment method identifier linked to the express checkout payment widget, while the value provides a mapping of additional query parameters for the widget.
     *
     * @api
     *
     * @param string $appPaymentMethodKey
     *
     * @return list<string>
     */
    public function getScriptQueryStringParametersByAppPaymentMethodKey(string $appPaymentMethodKey): array
    {
        return static::SCRIPT_QUERY_STRING_PARAMETERS_BY_APP_PAYMENT_METHOD_KEY[$appPaymentMethodKey] ?? [];
    }

    /**
     * Specification:
     * - Returns a map of script parameters for the Express Checkout widget.
     * - Script parameters are additional key value pairs you can add to the script tag to configure the Express Checkout widget behaviour you need.
     * - The key represents the app-specific payment method identifier linked to the express checkout payment widget, while the value provides a mapping of additional script parameters for the widget.
     *
     * @api
     *
     * @param string $appPaymentMethodKey
     *
     * @return list<string>
     */
    public function getScriptParametersByAppPaymentMethodKey(string $appPaymentMethodKey): array
    {
        return static::SCRIPT_PARAMETERS_BY_APP_PAYMENT_METHOD_KEY[$appPaymentMethodKey] ?? [];
    }

    /**
     * Specification:
     * - Returns the checkout step codes to skip in express checkout workflow.
     *
     * @api
     *
     * @return list<string>
     */
    public function getCheckoutStepsToSkipInExpressCheckoutWorkflow(): array
    {
        return static::CHECKOUT_STEPS_TO_SKIP_IN_EXPRESS_CHECKOUT_WORKFLOW;
    }

    /**
     * Specification:
     * - Returns a list of fields to be cleaned from the `QuoteTransfer` during the express checkout workflow.
     * - This method is used when the customer is redirected back to the cart page.
     *
     * @api
     *
     * @return list<string>
     */
    public function getQuoteFieldsToCleanInExpressCheckoutWorkflow(): array
    {
        return static::QUOTE_FIELDS_TO_CLEAN_IN_EXPRESS_CHECKOUT_WORKFLOW;
    }
}
