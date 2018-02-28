<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderItemEmbeddedForm extends AbstractType
{
    public const FILED_SEARCH_FIELD = 'searchField';
    public const FILED_SEARCH_QUERY = 'searchQuery';
    public const FILED_SKU = 'sku';
    public const FILED_QTY = 'qty';
    public const FILED_PRICE = 'price';
    public const FILED_PRICE_PANEL = 'pricePanel';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addSearchField($builder)
            ->addSearchQuery($builder)
            ->addSku($builder)
            ->addQty($builder)
            ->addPrice($builder)
            ->addPricePanel($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuickOrderItemTransfer::class,
        ]);
    }

    protected function getSearchFieldChoices()
    {
        return [
            'SKU / Name' => '',
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSearchField(FormBuilderInterface $builder): FormTypeInterface
    {
        $builder->add(static::FILED_SEARCH_FIELD, ChoiceType::class, [
            'choices' => $this->getSearchFieldChoices(),
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSearchQuery(FormBuilderInterface $builder): FormTypeInterface
    {
        $builder->add(static::FILED_SEARCH_QUERY, SearchType::class, [
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSku(FormBuilderInterface $builder): FormTypeInterface
    {
        $builder
            ->add(static::FILED_SKU, HiddenType::class, [
                'required' => false,
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addQty(FormBuilderInterface $builder): FormTypeInterface
    {
        $builder->add(static::FILED_QTY, IntegerType::class, [
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPrice(FormBuilderInterface $builder): FormTypeInterface
    {
        $builder->add(static::FILED_PRICE, HiddenType::class, [
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPricePanel(FormBuilderInterface $builder): FormTypeInterface
    {
        $builder->add(static::FILED_PRICE_PANEL, TextType::class, [
                'disabled' => true,
                'required' => false,
            ]);

        return $this;
    }
}
