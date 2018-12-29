<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductQuickAddWidget\Widget;

use Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\Form\FormInterface;
use SprykerShop\Yves\ProductQuickAddWidget\Form\ProductQuickAddForm;

/**
 * @method \SprykerShop\Yves\ProductQuickAddWidget\ProductQuickAddWidgetConfig getConfig()
 * @method \SprykerShop\Yves\ProductQuickAddWidget\ProductQuickAddWidgetFactory getFactory()
 */
class ProductQuickAddWidget extends AbstractWidget implements WidgetInterface
{
    protected const NAME = 'ProductQuickAddWidget';

    /**
     * @param string $title
     * @param string $submitButtonTitle
     * @param string $submitUrl
     * @param string $redirectRouteName
     * @param array $additionalRedirectData
     */
    public function __construct(
        string $title,
        string $submitButtonTitle,
        string $submitUrl,
        string $redirectRouteName,
        array $additionalRedirectData = []
    ) {
        $productQuickAddForm = $this->getProductQuickAddForm();

        $preparedAdditionalRedirectData = base64_encode(json_encode($additionalRedirectData));

        $productQuickAddForm->get(ProductQuickAddForm::FIELD_REDIRECT_ROUTE_NAME)->setData($redirectRouteName);
        $productQuickAddForm->get(ProductQuickAddForm::FIELD_ADDITIONAL_REDIRECT_PARAMETERS)->setData($preparedAdditionalRedirectData);

        $this->addParameter('title', $title)
            ->addParameter('submitButtonTitle', $submitButtonTitle)
            ->addParameter('submitUrl', $submitUrl)
            ->addParameter('form', $productQuickAddForm->createView());
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductQuickAddWidget/views/product-quick-add-form/product-quick-add-form.twig';
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getProductQuickAddForm(): FormInterface
    {
        return $this->getFactory()->getProductQuickAddForm();
    }
}
