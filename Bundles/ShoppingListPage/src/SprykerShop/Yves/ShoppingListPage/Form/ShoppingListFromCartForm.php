<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageConfig getConfig()
 */
class ShoppingListFromCartForm extends AbstractType
{
    public const FIELD_NEW_SHOPPING_LIST_NAME_INPUT = 'newShoppingListName';
    public const OPTION_SHOPPING_LISTS = 'OPTION_SHOPPING_LISTS';
    protected const FIELD_ID_QUOTE = 'idQuote';
    protected const FIELD_ID_SHOPPING_LIST = 'idShoppingList';
    protected const GLOSSARY_KEY_CART_ADD_TO_SHOPPING_LIST_FORM_PLACEHOLDER = 'cart.add-to-shopping-list.form.placeholder';
    protected const GLOSSARY_KEY_CART_ADD_TO_SHOPPING_LIST_FORM_ERROR_EMPTY_NAME = 'cart.add-to-shopping-list.form.error.empty_name';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_CREATE_FROM_CART_CHOOSE_SHOPPING_LIST = 'customer.account.shopping_list.create_from_cart.choose_shopping_list';
    protected const GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_CREATE_FROM_CART_NAME = 'customer.account.shopping_list.create_from_cart.name';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addQuoteTransferField($builder);
        $this->addShoppingListField($builder, $options);
        $this->addShoppingListNameField($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(static::OPTION_SHOPPING_LISTS);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addQuoteTransferField(FormBuilderInterface $builder): void
    {
        $builder->add(static::FIELD_ID_QUOTE, HiddenType::class);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    protected function addShoppingListField(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(static::FIELD_ID_SHOPPING_LIST, ChoiceType::class, [
            'choices' => $options[static::OPTION_SHOPPING_LISTS],
            'expanded' => false,
            'required' => true,
            'label' => static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_CREATE_FROM_CART_CHOOSE_SHOPPING_LIST,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addShoppingListNameField(FormBuilderInterface $builder): void
    {
        $builder->add(static::FIELD_NEW_SHOPPING_LIST_NAME_INPUT, TextType::class, [
            'label' => static::GLOSSARY_KEY_CUSTOMER_ACCOUNT_SHOPPING_LIST_CREATE_FROM_CART_NAME,
            'mapped' => false,
            'required' => false,
            'attr' => [
                'placeholder' => static::GLOSSARY_KEY_CART_ADD_TO_SHOPPING_LIST_FORM_PLACEHOLDER,
            ],
            'constraints' => [
                new Callback([
                    'callback' => $this->nameValidateCallback($builder),
                ]),
            ],
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Closure
     */
    protected function nameValidateCallback(FormBuilderInterface $builder): callable
    {
        return function ($object, ExecutionContextInterface $context) use ($builder) {
            $data = $builder->getData();
            if (!$object && !$data[static::FIELD_ID_SHOPPING_LIST]) {
                $context->buildViolation(static::GLOSSARY_KEY_CART_ADD_TO_SHOPPING_LIST_FORM_ERROR_EMPTY_NAME)
                    ->addViolation();
            }
        };
    }
}
