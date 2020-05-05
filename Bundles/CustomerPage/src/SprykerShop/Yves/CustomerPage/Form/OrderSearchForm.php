<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageConfig getConfig()
 */
class OrderSearchForm extends AbstractType
{
    public const FIELD_SEARCH_TYPE = 'searchType';
    public const FIELD_SEARCH_TEXT = 'searchText';
    public const FIELD_IS_ORDER_ITEMS_VISIBLE = 'isOrderItemsVisible';
    public const FIELD_ORDER_BY = 'orderBy';
    public const FIELD_ORDER_DIRECTION = 'orderDirection';
    public const FIELD_RESET = 'reset';
    public const FIELD_FILTERS = 'filters';

    public const OPTION_ORDER_SEARCH_TYPES = 'OPTION_ORDER_SEARCH_TYPES';
    public const OPTION_CURRENT_TIMEZONE = 'OPTION_CURRENT_TIMEZONE';
    public const OPTION_EXPANDABLE_DATA = 'OPTION_EXPANDABLE_DATA';

    public const FORM_NAME = 'orderSearchForm';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired([
            static::OPTION_ORDER_SEARCH_TYPES,
            static::OPTION_CURRENT_TIMEZONE,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::FORM_NAME;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setMethod(Request::METHOD_GET);

        $this->addSearchTypeField($builder, $options)
            ->addSearchTextField($builder)
            ->addIsOrderItemsVisibleField($builder)
            ->addOrderByField($builder)
            ->addOrderDirectionField($builder)
            ->addResetField($builder)
            ->addFiltersForm($builder, $options);

        $this->executeOrderSearchFormExpanderPlugins($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addSearchTypeField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_SEARCH_TYPE, ChoiceType::class, [
            'choices' => $options[static::OPTION_ORDER_SEARCH_TYPES],
            'data' => reset($options[static::OPTION_ORDER_SEARCH_TYPES]),
            'placeholder' => false,
            'required' => false,
            'label' => 'customer.order_history.search',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSearchTextField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SEARCH_TEXT, TextType::class, [
            'label' => false,
            'required' => false,
            'attr' => [
                'placeholder' => 'global.search',
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsOrderItemsVisibleField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_IS_ORDER_ITEMS_VISIBLE, CheckboxType::class, [
            'required' => false,
            'label' => 'customer.order_history.is_order_items_visible',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addOrderByField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ORDER_BY, HiddenType::class, [
            'required' => false,
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addOrderDirectionField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ORDER_DIRECTION, HiddenType::class, [
            'required' => false,
            'label' => false,
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addResetField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_RESET, HiddenType::class, [
            'required' => false,
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addFiltersForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_FILTERS,
            OrderSearchFiltersForm::class,
            [
                static::OPTION_CURRENT_TIMEZONE => $options[static::OPTION_CURRENT_TIMEZONE],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    protected function executeOrderSearchFormExpanderPlugins(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $orderSearchFormExpanderPlugins = $this->getFactory()
            ->createCustomerFormFactory()
            ->getOrderSearchFormExpanderPlugins();

        foreach ($orderSearchFormExpanderPlugins as $orderSearchFormExpanderPlugin) {
            $builder = $orderSearchFormExpanderPlugin->expand($builder, $options);
        }

        return $builder;
    }
}
