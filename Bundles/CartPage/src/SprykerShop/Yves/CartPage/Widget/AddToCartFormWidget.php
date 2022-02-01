<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\Form\FormView;

/**
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 */
class AddToCartFormWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_ADD_TO_CART_FORM = 'addToCartForm';

    /**
     * @var string
     */
    protected const PARAMETER_CONFIG = 'config';

    /**
     * @var string
     */
    protected const PARAMETER_PRODUCT = 'product';

    /**
     * @var string
     */
    protected const PARAMETER_IS_ADD_TO_CART_DISABLED = 'isAddToCartDisabled';

    /**
     * @var string
     */
    protected const PARAMETER_QUANTITY_OPTIONS = 'quantityOptions';

    /**
     * @var string
     */
    protected const PARAMETER_FORM_NAME_POSTFIX = 'formNamePostfix';

    /**
     * @param array<string, mixed> $config
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param bool $isAddToCartDisabled
     * @param array<int, mixed> $quantityOptions Contains the selectable quantity options; each option is structured as ['label' => 1, 'value' => 1]
     */
    public function __construct(array $config, ProductViewTransfer $productViewTransfer, bool $isAddToCartDisabled, array $quantityOptions = [])
    {
        $this->addAddToCartFormParameter();
        $this->addConfigParameter($config);
        $this->addProductParameter($productViewTransfer);
        $this->addIsAddToCartDisabledParameter($isAddToCartDisabled);
        $this->addQuantityOptionsParameter($quantityOptions);
        $this->addFormNamePostfixParameter();

        $this->expandFormWidgetParameters($productViewTransfer);
    }

    /**
     * @return void
     */
    protected function addAddToCartFormParameter(): void
    {
        $this->addParameter(static::PARAMETER_ADD_TO_CART_FORM, $this->createAddToCartFormView());
    }

    /**
     * @param array<string, mixed> $config
     *
     * @return void
     */
    protected function addConfigParameter(array $config): void
    {
        $this->addParameter(static::PARAMETER_CONFIG, $config);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    protected function addProductParameter(ProductViewTransfer $productViewTransfer): void
    {
        $this->addParameter(static::PARAMETER_PRODUCT, $productViewTransfer);
    }

    /**
     * @param bool $isAddToCartDisabled
     *
     * @return void
     */
    protected function addIsAddToCartDisabledParameter(bool $isAddToCartDisabled): void
    {
        $this->addParameter(static::PARAMETER_IS_ADD_TO_CART_DISABLED, $isAddToCartDisabled);
    }

    /**
     * @param array<int, mixed> $quantityOptions
     *
     * @return void
     */
    protected function addQuantityOptionsParameter(array $quantityOptions): void
    {
        $this->addParameter(static::PARAMETER_QUANTITY_OPTIONS, $quantityOptions);
    }

    /**
     * @return void
     */
    protected function addFormNamePostfixParameter(): void
    {
        $this->addParameter(static::PARAMETER_FORM_NAME_POSTFIX, '');
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'AddToCartFormWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CartPage/views/add-to-cart-form/add-to-cart-form.twig';
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    protected function createAddToCartFormView(): FormView
    {
        return $this->getFactory()
            ->createCartPageFormFactory()
            ->getAddToCartForm()
            ->createView();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    protected function expandFormWidgetParameters(ProductViewTransfer $productViewTransfer): void
    {
        $formParameters = $this->getParameters();
        foreach ($this->getFactory()->getAddToCartFormWidgetParameterExpanderPlugins() as $addToCartFormWidgetParameterExpanderPlugin) {
            $formParameters = $addToCartFormWidgetParameterExpanderPlugin->expand($formParameters, $productViewTransfer);
        }

        foreach ($formParameters as $formParameterName => $formParameterValue) {
            $this->addParameter($formParameterName, $formParameterValue);
        }
    }
}
