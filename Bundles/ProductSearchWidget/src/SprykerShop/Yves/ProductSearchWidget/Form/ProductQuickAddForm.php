<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use SprykerShop\Yves\ShopUi\Form\Type\FormattedIntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerShop\Yves\ProductSearchWidget\ProductSearchWidgetConfig getConfig()
 * @method \SprykerShop\Yves\ProductSearchWidget\ProductSearchWidgetFactory getFactory()
 */
class ProductQuickAddForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_SKU = 'sku';

    /**
     * @var string
     */
    protected const FIELD_QUANTITY = 'quantity';

    /**
     * @var string
     */
    public const FIELD_REDIRECT_ROUTE_NAME = 'redirect-route-name';

    /**
     * @var string
     */
    public const FIELD_REDIRECT_ROUTE_PARAMETERS = 'redirect-route-parameters';

    /**
     * @var string
     */
    public const OPTION_LOCALE = 'locale';

    /**
     * @var string
     */
    protected const FORM_NAME = 'productQuickAddForm';

    /**
     * @var int
     */
    protected const MAX_QUANTITY_VALUE = 2147483647; // 32 bit integer

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_QUANTITY_REQUIRED = 'product_quick_add_widget.form.error.quantity.required';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_QUANTITY_MAX_VALUE_CONSTRAINT = 'product_quick_add_widget.form.error.quantity.max_value_constraint';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_REDIRECT_ROUTE_EMPTY = 'product_quick_add_widget.form.error.redirect_route_empty';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_REDIRECT_SKU_EMPTY = 'product_quick_add_widget.form.error.sku.empty';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return '';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::OPTION_LOCALE);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addQuantity($builder, $options)
            ->addSku($builder)
            ->addRedirectRouteName($builder)
            ->addAdditionalRedirectParameters($builder);

        $this->executeProductQuickAddFormExpanderPlugins($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSku(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SKU, HiddenType::class, [
            'required' => true,
            'label' => false,
            'constraints' => [
                $this->createNotBlankConstraint(static::ERROR_MESSAGE_REDIRECT_SKU_EMPTY),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addRedirectRouteName(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_REDIRECT_ROUTE_NAME, HiddenType::class, [
            'required' => true,
            'label' => false,
            'constraints' => [
                $this->createNotBlankConstraint(static::ERROR_MESSAGE_REDIRECT_ROUTE_EMPTY),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addQuantity(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_QUANTITY, FormattedIntegerType::class, [
                'required' => true,
                'label' => false,
                'attr' => ['min' => 1, 'data-qa' => 'product-quick-add-form-quantity-input'],
                'locale' => $options[static::OPTION_LOCALE],
                'constraints' => [
                    $this->createNotBlankConstraint(static::ERROR_MESSAGE_QUANTITY_REQUIRED),
                    $this->createMinLengthConstraint(static::ERROR_MESSAGE_QUANTITY_REQUIRED),
                    $this->createLessThanOrEqualConstraint(
                        static::MAX_QUANTITY_VALUE,
                        static::ERROR_MESSAGE_QUANTITY_MAX_VALUE_CONSTRAINT,
                    ),
                ],
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAdditionalRedirectParameters(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_REDIRECT_ROUTE_PARAMETERS, HiddenType::class, [
            'required' => false,
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @param string $message
     *
     * @return \Symfony\Component\Validator\Constraints\Length
     */
    protected function createMinLengthConstraint(string $message): Length
    {
        return new Length([
            'min' => 1,
            'minMessage' => $message,
        ]);
    }

    /**
     * @param string $message
     *
     * @return \Symfony\Component\Validator\Constraints\NotBlank
     */
    protected function createNotBlankConstraint(string $message): NotBlank
    {
        return new NotBlank([
            'message' => $message,
        ]);
    }

    /**
     * @param int $maxValue
     * @param string $message
     *
     * @return \Symfony\Component\Validator\Constraints\LessThanOrEqual
     */
    protected function createLessThanOrEqualConstraint(int $maxValue, string $message): LessThanOrEqual
    {
        return new LessThanOrEqual([
            'value' => $maxValue,
            'message' => $message,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, string> $options
     *
     * @return void
     */
    protected function executeProductQuickAddFormExpanderPlugins(FormBuilderInterface $builder, array $options): void
    {
        foreach ($this->getFactory()->getProductQuickAddFormExpanderPlugins() as $productQuickAddFormExpanderPlugin) {
            $productQuickAddFormExpanderPlugin->expand($builder, $options);
        }
    }
}
