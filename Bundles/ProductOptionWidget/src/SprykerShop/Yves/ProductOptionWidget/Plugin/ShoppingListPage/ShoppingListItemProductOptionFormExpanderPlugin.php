<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerShop\Yves\ProductOptionWidget\Plugin\ShoppingListPage;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ProductOptionWidget\Form\ShoppingListItemProductOptionForm;
use SprykerShop\Yves\ShoppingListPageExtension\Dependency\Plugin\ShoppingListItemFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

class ShoppingListItemProductOptionFormExpanderPlugin extends AbstractPlugin implements ShoppingListItemFormExpanderPluginInterface
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            ShoppingListItemTransfer::PRODUCT_OPTIONS,
            ShoppingListItemProductOptionForm::class,
            [
//                'data_class' => ShoppingListItemTransfer::class, //todo:check transfer
                'label' => false,
            ]
        );
    }
}
