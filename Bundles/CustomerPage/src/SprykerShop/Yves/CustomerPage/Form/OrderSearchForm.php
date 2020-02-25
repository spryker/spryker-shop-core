<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageConfig getConfig()
 */
class OrderSearchForm extends AbstractType
{
    public const FIELD_SEARCH_GROUP = 'searchGroup';
    public const FIELD_SEARCH_TEXT = 'searchText';
    public const FIELD_DATE_FROM = 'dateFrom';
    public const FIELD_DATE_TO = 'dateTo';
    public const FIELD_IS_ORDER_ITEMS_VISIBLE = 'isOrderItemsVisible';

    public const OPTION_ORDER_SEARCH_GROUPS = 'OPTION_ORDER_SEARCH_GROUPS';
    public const OPTION_CURRENT_TIMEZONE = 'OPTION_CURRENT_TIMEZONE';

    public const FORM_NAME = 'orderSearchForm';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            static::OPTION_ORDER_SEARCH_GROUPS,
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
        $this->addSearchGroupField($builder, $options)
            ->addSearchTextField($builder)
            ->addDateFromField($builder, $options)
            ->addDateToField($builder, $options)
            ->addIsOrderItemsVisibleField($builder);

        $this->executeOrderSearchFormExpanderPlugins($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addSearchGroupField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_SEARCH_GROUP, ChoiceType::class, [
            'choices' => $options[static::OPTION_ORDER_SEARCH_GROUPS],
            'data' => reset($options[static::OPTION_ORDER_SEARCH_GROUPS]),
            'placeholder' => false,
            'label' => false,
            'required' => false,
            'choice_translation_domain' => true,
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
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addDateFromField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_DATE_FROM, DateTimeType::class, [
            'label' => false,
            'widget' => 'single_text',
            'required' => false,
            'view_timezone' => $options[static::OPTION_CURRENT_TIMEZONE],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addDateToField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_DATE_TO, DateTimeType::class, [
            'label' => false,
            'widget' => 'single_text',
            'required' => false,
            'view_timezone' => $options[static::OPTION_CURRENT_TIMEZONE],
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
            'label' => false,
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    protected function executeOrderSearchFormExpanderPlugins(FormBuilderInterface $builder, array $options): void
    {
        $orderSearchFormExpanderPlugins = $this->getFactory()
            ->createCustomerFormFactory()
            ->getOrderSearchFormExpanderPlugins();

        foreach ($orderSearchFormExpanderPlugins as $orderSearchFormExpanderPlugin) {
            $orderSearchFormExpanderPlugin->expand($builder, $options);
        }
    }
}
