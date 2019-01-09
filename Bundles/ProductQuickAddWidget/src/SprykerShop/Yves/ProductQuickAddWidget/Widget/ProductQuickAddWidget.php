<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductQuickAddWidget\Widget;

use Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use SprykerShop\Yves\ProductQuickAddWidget\Form\ProductQuickAddForm;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\ProductQuickAddWidget\ProductQuickAddWidgetFactory getFactory()
 */
class ProductQuickAddWidget extends AbstractWidget implements WidgetInterface
{
    protected const NAME = 'ProductQuickAddWidget';

    /**
     * @param string $title
     * @param string $submitButtonTitle
     * @param string $redirectRouteName
     * @param array $additionalRedirectData
     */
    public function __construct(
        string $title,
        string $submitButtonTitle,
        string $redirectRouteName,
        array $additionalRedirectData = []
    ) {
        $productQuickAddForm = $this->getProductQuickAddForm();

        $preparedAdditionalRedirectData = $this->encodeAdditionalData($additionalRedirectData);

        $productQuickAddForm->get(ProductQuickAddForm::FIELD_REDIRECT_ROUTE_NAME)->setData($redirectRouteName);
        $productQuickAddForm->get(ProductQuickAddForm::FIELD_ADDITIONAL_REDIRECT_PARAMETERS)->setData($preparedAdditionalRedirectData);

        $this->addParameter('title', $title)
            ->addParameter('submitButtonTitle', $submitButtonTitle)
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
        return '@ProductQuickAddWidget/views/product-quick-add/product-quick-add.twig';
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

        return base64_encode(
            $utilEncodingService->encodeJson($data)
        );
    }
}
