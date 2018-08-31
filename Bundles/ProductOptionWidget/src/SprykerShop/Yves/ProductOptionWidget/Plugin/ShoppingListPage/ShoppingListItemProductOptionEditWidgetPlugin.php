<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOptionWidget\Plugin\ShoppingListPage;

use ArrayObject;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShoppingListPage\Dependency\Plugin\ProductOptionWidget\ShoppingListItemProductOptionEditWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductOptionWidget\ProductOptionWidgetFactory getFactory()
 */
class ShoppingListItemProductOptionEditWidgetPlugin extends AbstractWidgetPlugin implements ShoppingListItemProductOptionEditWidgetPluginInterface
{
    /**
     * @param \Symfony\Component\Form\ChoiceList\View\ChoiceView[] $productOptionGroups //todo: check parameter
     * @param string $name
     *
     * @return void
     */
    public function initialize(array $productOptionGroups, string $name): void
    {
        $mappedProductOptionGroups = $this->mapProductOptionGroups($productOptionGroups);

        $this->addParameter(
            'productOptionGroups',
            new ArrayObject($mappedProductOptionGroups)
        );

        $this->addParameter(
            'name',
            $name
        );
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
        return '@ProductOptionWidget/views/shopping-list-option-configurator/shopping-list-option-configurator.twig';
    }

    /**
     * @param \Symfony\Component\Form\ChoiceList\View\ChoiceView[] $productOptionGroups
     *
     * @return \ArrayObject
     */
    protected function mapProductOptionGroups(array $productOptionGroups): ArrayObject
    {
        //todo: check and refactor;
        $mappedProductOptionGroups = [];
        foreach ($productOptionGroups as $productOptionGroup) {
            $mappedProductOptionGroups[] = $productOptionGroup->data;
        }

        return new ArrayObject($mappedProductOptionGroups);
    }
}
