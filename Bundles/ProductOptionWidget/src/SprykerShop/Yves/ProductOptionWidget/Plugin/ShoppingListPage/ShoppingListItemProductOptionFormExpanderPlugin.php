<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace SprykerShop\Yves\ProductOptionWidget\Plugin\ShoppingListPage;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ProductOptionWidget\Form\ShoppingListItemProductOptionForm;
use SprykerShop\Yves\ShoppingListPageExtension\Dependency\Plugin\ShoppingListItemFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @method \SprykerShop\Yves\ProductOptionWidget\ProductOptionWidgetFactory getFactory()
 */
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
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder) {
            $form = $event->getForm();
            /** @var \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer */
            $shoppingListItemTransfer = $event->getData();
            $option[ShoppingListItemProductOptionForm::PRODUCT_OPTION_GROUP_KEY] = $this->getProductOptionGroups($shoppingListItemTransfer);
            $form->add(
                ShoppingListItemTransfer::PRODUCT_OPTIONS,
                ShoppingListItemProductOptionForm::class,
                $option
            );
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductOptionGroupStorageTransfer[]|null
     */
    protected function getProductOptionGroups(ShoppingListItemTransfer $shoppingListItemTransfer)
    {
        $storageProductOptionGroupCollectionTransfer = $this->getStorageProductOptionGroupCollectionTransfer($shoppingListItemTransfer);
        if (!$storageProductOptionGroupCollectionTransfer) {
            return new ArrayObject();
        }
        $storageProductOptionGroupCollectionTransfer = $this->hydrateStorageProductOptionGroupCollectionTransfer($storageProductOptionGroupCollectionTransfer, $shoppingListItemTransfer);

        return $storageProductOptionGroupCollectionTransfer->getProductOptionGroups();
    }

    /**
     * todo:refactor block
     * @param \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer $storageProductOptionGroupCollectionTransfer
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer
     */
    protected function hydrateStorageProductOptionGroupCollectionTransfer(
        ProductAbstractOptionStorageTransfer $storageProductOptionGroupCollectionTransfer,
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ProductAbstractOptionStorageTransfer {

        $selectedProductOptionIds = [];
        foreach ($shoppingListItemTransfer->getProductOptions() as $productOptionTransfer) {
            $selectedProductOptionIds[] = $productOptionTransfer->getIdProductOptionValue();
        }

        foreach ($storageProductOptionGroupCollectionTransfer->getProductOptionGroups() as $productOptionGroup) {
            foreach ($productOptionGroup->getProductOptionValues() as $productOptionValue) {
                if (in_array($productOptionValue->getIdProductOptionValue(), $selectedProductOptionIds)) {
                    $productOptionValue->setIsSelected(true);
                }
            }
        }

        return $storageProductOptionGroupCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer|null
     */
    protected function getStorageProductOptionGroupCollectionTransfer(ShoppingListItemTransfer $shoppingListItemTransfer)
    {
        return $this
            ->getFactory()
            ->getProductOptionStorageClient()
            ->getProductOptionsForCurrentStore($shoppingListItemTransfer->getIdProductAbstract());
    }
}
