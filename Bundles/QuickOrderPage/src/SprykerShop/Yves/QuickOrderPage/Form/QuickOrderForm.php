<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form;

use Generated\Shared\Transfer\QuickOrderTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use SprykerShop\Yves\QuickOrderPage\Form\EventSubscriber\QuickOrderItemsEventSubscriber;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\QuickOrderPage\QuickOrderPageFactory getFactory()
 * @method \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig getConfig()
 */
class QuickOrderForm extends AbstractType
{
    /**
     * @var string
     */
    protected const SUBMIT_BUTTON_ADD_TO_CART = 'addToCart';

    /**
     * @var string
     */
    protected const SUBMIT_BUTTON_CREATE_ORDER = 'createOrder';

    /**
     * @var string
     */
    protected const FIELD_ITEMS = 'items';

    /**
     * @var string
     */
    public const OPTION_LOCALE = 'locale';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addItemsCollection($builder, $options);

        $builder->addEventSubscriber(new QuickOrderItemsEventSubscriber());
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuickOrderTransfer::class,
        ]);

        $resolver->setRequired(static::OPTION_LOCALE);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addItemsCollection(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_ITEMS,
            CollectionType::class,
            [
                'entry_type' => QuickOrderItemEmbeddedForm::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
                'entry_options' => [
                    'label' => false,
                    static::OPTION_LOCALE => $options[static::OPTION_LOCALE],
                ],
                'constraints' => [
                        $this->getFactory()->createItemsFieldConstraint(),
                ],
            ],
        );

        return $this;
    }
}
