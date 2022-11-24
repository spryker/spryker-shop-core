<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Form;

use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageConfig getConfig()
 */
class ShoppingListUpdateForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_NAME = 'name';

    /**
     * @var string
     */
    public const FIELD_ID = 'idShoppingList';

    /**
     * @var string
     */
    public const FIELD_ITEMS = 'items';

    /**
     * @var string
     */
    public const OPTION_LOCALE = 'locale';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => ShoppingListTransfer::class,
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
        $this->addNameField($builder);
        $this->addIdField($builder);
        $this->addItemsField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addNameField(FormBuilderInterface $builder): void
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => false,
            'required' => true,
            'constraints' => [
                new NotBlank(),
            ],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addIdField(FormBuilderInterface $builder): void
    {
        $builder->add(static::FIELD_ID, HiddenType::class);
        $builder->get(static::FIELD_ID)
            ->addModelTransformer(new CallbackTransformer(
                function ($id) {
                    return $id;
                },
                function ($idString) {
                    return (int)$idString;
                },
            ));
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    protected function addItemsField(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(static::FIELD_ITEMS, CollectionType::class, [
            'required' => false,
            'label' => false,
            'entry_type' => ShoppingListItemForm::class,
            'entry_options' => [
                'locale' => $options[static::OPTION_LOCALE],
            ],
        ]);
    }
}
