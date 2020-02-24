<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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

    public const OPTION_ORDER_SEARCH_GROUPS = 'OPTION_ORDER_SEARCH_GROUPS';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::OPTION_ORDER_SEARCH_GROUPS);
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
            ->addSearchTextField($builder);

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
