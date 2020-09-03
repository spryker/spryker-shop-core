<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Form;

use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use SprykerShop\Yves\ProductConfiguratorGatewayPage\Form\Constraint\ProductConfigurationIdentifier;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageFactory getFactory()
 * @method \SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageConfig getConfig()
 */
class ProductConfiguratorRequestDataForm extends AbstractType implements DataMapperInterface
{
    public const FILED_SKU = 'sku';
    public const FILED_QUANTITY = 'quantity';
    public const FILED_SOURCE_TYPE = 'source-type';
    public const FILED_ITEM_GROUP_KEY = 'item-group-key';

    protected const VALIDATION_SOURCE_NOT_BLANK_MESSAGE = 'product_configuration.source_not_blank';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addSkuField($builder)
            ->addQuantityField($builder)
            ->addSourceField($builder)
            ->addItemGroupKeyField($builder);

        $builder->setDataMapper($this);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => ProductConfiguratorRequestDataTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSkuField(FormBuilderInterface $builder)
    {
        $builder->add(static::FILED_SKU, HiddenType::class, [
            'required' => true,
            'constraints' => [
                new ProductConfigurationIdentifier(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addItemGroupKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FILED_ITEM_GROUP_KEY, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addQuantityField(FormBuilderInterface $builder)
    {
        $builder->add(static::FILED_QUANTITY, HiddenType::class, [
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSourceField(FormBuilderInterface $builder)
    {
        $builder->add(static::FILED_SOURCE_TYPE, HiddenType::class, [
            'required' => true,
            'constraints' => [
                new NotBlank(['message' => static::VALIDATION_SOURCE_NOT_BLANK_MESSAGE]),
                new Choice([
                    'choices' => [
                            $this->getConfig()->getCartSourceType(),
                            $this->getConfig()->getPdpSourceType(),
                        ],
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param mixed $viewData
     * @param \Symfony\Component\Form\FormInterface[]|\Traversable $forms
     *
     * @return void
     */
    public function mapDataToForms($viewData, $forms)
    {
    }

    /**
     * @param \Symfony\Component\Form\FormInterface[]|\Traversable $forms
     * @param mixed $viewData
     *
     * @return void
     */
    public function mapFormsToData($forms, &$viewData): void
    {
        $viewData = $this->getFactory()
            ->createProductConfiguratorRequestDataMapper()
            ->mapProductConfiguratorRequestDataFormToProductConfiguratorRequestDataTransfer(
                new ProductConfiguratorRequestDataTransfer(),
                iterator_to_array($forms)
            );
    }
}
