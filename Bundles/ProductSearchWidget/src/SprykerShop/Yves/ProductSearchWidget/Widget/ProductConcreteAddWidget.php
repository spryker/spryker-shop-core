<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\Widget;

use Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use SprykerShop\Yves\ProductSearchWidget\Form\ProductQuickAddForm;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\ProductSearchWidget\ProductSearchWidgetFactory getFactory()
 */
class ProductConcreteAddWidget extends AbstractWidget implements WidgetInterface
{
    protected const NAME = 'ProductConcreteAddWidget';

    /**
     * @param string $title
     * @param string $submitButtonTitle
     * @param string $redirectRouteName
     * @param bool $isVerticalLayout
     * @param array $additionalRedirectData
     */
    public function __construct(
        string $title,
        string $submitButtonTitle,
        string $redirectRouteName,
        bool $isVerticalLayout = false,
        array $additionalRedirectData = []
    ) {
        $productQuickAddForm = $this->getProductQuickAddForm();

        $preparedAdditionalRedirectData = $this->encodeAdditionalData($additionalRedirectData);

        $productQuickAddForm->get(ProductQuickAddForm::FIELD_REDIRECT_ROUTE_NAME)->setData($redirectRouteName);
        $productQuickAddForm->get(ProductQuickAddForm::FIELD_REDIRECT_ROUTE_PARAMETERS)->setData($preparedAdditionalRedirectData);

        $this->addParameter('title', $title)
            ->addParameter('submitButtonTitle', $submitButtonTitle)
            ->addParameter('form', $productQuickAddForm->createView())
            ->addParameter('verticalLayout', $isVerticalLayout);
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductSearchWidget/views/product-quick-add/product-quick-add.twig';
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getProductQuickAddForm(): FormInterface
    {
        return $this->getFactory()->getProductQuickAddForm();
    }

    /**
     * @param array $data
     *
     * @return string
     */
    protected function encodeAdditionalData(array $data): string
    {
        $utilEncodingService = $this->getFactory()->getUtilEncodingService();

        return urlencode(
            $utilEncodingService->encodeJson($data)
        );
    }
}
