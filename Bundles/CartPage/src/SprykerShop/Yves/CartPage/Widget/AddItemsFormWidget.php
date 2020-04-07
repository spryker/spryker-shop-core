<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\Form\FormView;

/**
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 */
class AddItemsFormWidget extends AbstractWidget
{
    /**
     * @param array $config
     * @param array $data
     * @param array $productViewTransfers
     */
    public function __construct(array $config, array $data, array $productViewTransfers)
    {
        $this->addParameter('config', $config);
        $this->addParameter('addToCartForm', $this->getAddToCartFormView());
        $this->addParameter('data', $data);
        $this->addParameter('products', $productViewTransfers);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'AddItemsFormWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CartPage/views/add-items-form/add-items-form.twig';
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    protected function getAddToCartFormView(): FormView
    {
        return $this->getFactory()
            ->createCartPageFormFactory()
            ->getAddItemsForm()
            ->createView();
    }
}
