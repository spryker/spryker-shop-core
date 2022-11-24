<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Form;

use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use SprykerShop\Yves\ShopUi\Form\Type\FormattedIntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

/**
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageFactory getFactory()
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageConfig getConfig()
 */
class ShoppingListItemForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_QUANTITY = 'quantity';

    /**
     * @var int
     */
    protected const MAX_QUANTITY_RANGE = 2147483647; // 32 bit integer

    /**
     * @var int
     */
    protected const MIN_QUANTITY_RANGE = 1;

    /**
     * @var string
     */
    protected const OPTION_LOCALE = 'locale';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => ShoppingListItemTransfer::class,
            static::OPTION_LOCALE => null,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addQuantityField($builder, $options);

        $this->addFormExpanders($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addQuantityField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_QUANTITY, FormattedIntegerType::class, [
            'locale' => $options[static::OPTION_LOCALE],
            'constraints' => [
                new NotBlank(),
                new Range([
                    'min' => static::MIN_QUANTITY_RANGE,
                    'max' => static::MAX_QUANTITY_RANGE,
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    protected function addFormExpanders(FormBuilderInterface $builder, array $options): void
    {
        foreach ($this->getFactory()->getShoppingListItemFormExpanderPlugins() as $shoppingItemFormTypeExpanderPlugin) {
            $shoppingItemFormTypeExpanderPlugin->buildForm($builder, $options);
        }
    }
}
