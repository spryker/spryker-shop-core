<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetFactory getFactory()
 * @method \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetConfig getConfig()
 */
class ExpressCheckoutForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_CSRF_TOKEN_NAME = 'csrfTokenName';

    /**
     * @var string
     */
    protected const OPTION_ALLOW_EXTRA_FIELDS = 'allow_extra_fields';

    /**
     * @var string
     */
    protected const OPTION_CSRF_FIELD_NAME = 'csrf_field_name';

    /**
     * @var string
     */
    protected const OPTION_CSRF_TOKEN_ID = 'csrf_token_id';

    /**
     * @var string
     */
    protected const FIELD_PAYMENT_PROVIDER = 'paymentProvider';

    /**
     * @var string
     */
    protected const FIELD_PAYMENT_METHOD = 'paymentMethod';

    /**
     * @var string
     */
    protected const FIELD_NAME_CSRF_TOKEN = 'csrfToken';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::OPTION_CSRF_TOKEN_NAME);
        $resolver->setDefaults([
            static::OPTION_ALLOW_EXTRA_FIELDS => true,
            static::OPTION_CSRF_FIELD_NAME => static::FIELD_NAME_CSRF_TOKEN,
            static::OPTION_CSRF_TOKEN_NAME => null,
        ]);

        $resolver->setDefault(static::OPTION_CSRF_TOKEN_ID, function (Options $options) {
            return $options[static::OPTION_CSRF_TOKEN_NAME];
        });

        $resolver->setAllowedTypes(static::OPTION_CSRF_TOKEN_NAME, ['null', 'string']);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addPaymentProviderField($builder)
            ->addPaymentMethodField($builder)
            ->addModelTransformer($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPaymentProviderField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PAYMENT_PROVIDER, TextType::class, [
            'constraints' => [$this->createNotBlankConstraint(), $this->createLengthConstraint()],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPaymentMethodField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PAYMENT_METHOD, TextType::class, [
            'constraints' => [$this->createNotBlankConstraint(), $this->createLengthConstraint()],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addModelTransformer(FormBuilderInterface $builder)
    {
        $builder->addModelTransformer($this->getFactory()->createArrayToInitializePreOrderPaymentRequestTransferTransformer());

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\NotBlank
     */
    protected function createNotBlankConstraint(): NotBlank
    {
        return new NotBlank();
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\Length
     */
    protected function createLengthConstraint(): Length
    {
        return new Length(['min' => 1]);
    }
}
