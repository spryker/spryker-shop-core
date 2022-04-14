<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentPage\Form;

use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\AbstractSubFormType;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use SprykerShop\Yves\CheckoutPage\Form\StepEngine\ExtraOptionsSubFormInterface;
use SprykerShop\Yves\PaymentPage\Exception\InvalidPaymentMethodException;
use SprykerShop\Yves\PaymentPage\Form\StepEngine\StandaloneSubFormInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentForeignSubForm extends AbstractSubFormType implements StandaloneSubFormInterface, ExtraOptionsSubFormInterface
{
    /**
     * @var string
     */
    protected const OPTION_PAYMENT_METHOD_TRANSFER = 'paymentMethodTransfer';

    /**
     * @var string
     */
    protected const FIELD_PAYMENT_METHOD_NAME = 'paymentMethodName';

    /**
     * @var string
     */
    protected const FIELD_PAYMENT_PROVIDER_NAME = 'paymentProviderName';

    /**
     * @var \Generated\Shared\Transfer\PaymentMethodTransfer|null
     */
    protected $paymentMethodTransfer;

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return $this
     */
    public function setPaymentMethodTransfer(PaymentMethodTransfer $paymentMethodTransfer)
    {
        $this->paymentMethodTransfer = $paymentMethodTransfer;

        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function getExtraOptions(): array
    {
        return [
            static::OPTION_PAYMENT_METHOD_TRANSFER => $this->paymentMethodTransfer,
        ];
    }

    /**
     * @return string
     */
    public function getLabelName(): string
    {
        return (string)$this->getPaymentMethodTransfer()->getLabelName();
    }

    /**
     * @return string
     */
    public function getGroupName(): string
    {
        return (string)$this->getPaymentMethodTransfer()->getGroupName();
    }

    /**
     * @return string
     */
    public function getPropertyPath(): string
    {
        return sprintf(
            '%s[%s]',
            PaymentTransfer::FOREIGN_PAYMENTS,
            $this->getPaymentMethodTransfer()->getPaymentMethodKey(),
        );
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return (string)$this->getPaymentMethodTransfer()->getPaymentMethodKey();
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ])->setRequired([
            SubFormInterface::OPTIONS_FIELD_NAME,
            static::OPTION_PAYMENT_METHOD_TRANSFER,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $paymentMethodTransfer = $options[static::OPTION_PAYMENT_METHOD_TRANSFER];

        $builder->add(static::FIELD_PAYMENT_METHOD_NAME, HiddenType::class, [
            'data' => $paymentMethodTransfer->getLabelName(),
        ]);
        $builder->add(static::FIELD_PAYMENT_PROVIDER_NAME, HiddenType::class, [
            'data' => $paymentMethodTransfer->getPaymentProvider()->getPaymentProviderKey(),
        ]);
    }

    /**
     * @throws \SprykerShop\Yves\PaymentPage\Exception\InvalidPaymentMethodException
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    protected function getPaymentMethodTransfer(): PaymentMethodTransfer
    {
        if ($this->paymentMethodTransfer === null) {
            throw new InvalidPaymentMethodException('PaymentMethod should be set first.');
        }

        return $this->paymentMethodTransfer;
    }

    /**
     * @return string
     */
    protected function getTemplatePath(): string
    {
        return '';
    }
}
