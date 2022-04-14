<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Form\Steps;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormProviderNameInterface;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;
use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface;
use SprykerShop\Yves\CheckoutPage\Form\StepEngine\ExtraOptionsSubFormInterface;
use SprykerShop\Yves\CheckoutPage\Form\StepEngine\StandaloneSubFormInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerShop\Yves\CheckoutPage\CheckoutPageFactory getFactory()
 * @method \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 */
class PaymentForm extends AbstractType
{
    public const PAYMENT_PROPERTY_PATH = QuoteTransfer::PAYMENT;

    public const PAYMENT_SELECTION = PaymentTransfer::PAYMENT_SELECTION;

    public const PAYMENT_SELECTION_PROPERTY_PATH = self::PAYMENT_PROPERTY_PATH . '.' . self::PAYMENT_SELECTION;

    /**
     * @var string
     */
    protected const VALIDATION_NOT_BLANK_MESSAGE = 'validation.not_blank';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'paymentForm';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addPaymentMethods($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addPaymentMethods(FormBuilderInterface $builder, array $options)
    {
        $paymentMethodSubForms = $this->getPaymentMethodSubForms();

        $this->addPaymentMethodChoices($builder, $paymentMethodSubForms)
            ->addPaymentMethodSubForms($builder, $paymentMethodSubForms, $options);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<\Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface> $paymentMethodSubForms
     *
     * @return $this
     */
    protected function addPaymentMethodChoices(FormBuilderInterface $builder, array $paymentMethodSubForms)
    {
        $builder->add(
            static::PAYMENT_SELECTION,
            ChoiceType::class,
            [
                'choices' => $this->getPaymentMethodChoices($paymentMethodSubForms),
                'choice_name' => function ($choice, $key) use ($paymentMethodSubForms) {
                    $paymentMethodSubForm = $paymentMethodSubForms[$key];

                    return $paymentMethodSubForm->getName();
                },
                'choice_label' => function ($choice, $key) use ($paymentMethodSubForms) {
                    $paymentMethodSubForm = $paymentMethodSubForms[$key];

                    if ($paymentMethodSubForm instanceof StandaloneSubFormInterface) {
                        return $paymentMethodSubForm->getLabelName();
                    }

                    return $paymentMethodSubForm->getName();
                },
                'group_by' => function ($choice, $key) use ($paymentMethodSubForms) {
                    $paymentMethodSubForm = $paymentMethodSubForms[$key];

                    if ($paymentMethodSubForm instanceof StandaloneSubFormInterface) {
                        return $paymentMethodSubForm->getGroupName();
                    }

                    if ($paymentMethodSubForm instanceof SubFormProviderNameInterface) {
                        return sprintf('checkout.payment.provider.%s', $paymentMethodSubForm->getProviderName());
                    }

                    return '';
                },
                'label' => false,
                'required' => true,
                'expanded' => true,
                'multiple' => false,
                'placeholder' => false,
                'property_path' => static::PAYMENT_SELECTION_PROPERTY_PATH,
                'constraints' => [
                    $this->createNotBlankConstraint(),
                ],
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<\Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface> $paymentMethodSubForms
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addPaymentMethodSubForms(FormBuilderInterface $builder, array $paymentMethodSubForms, array $options)
    {
        foreach ($paymentMethodSubForms as $paymentMethodSubForm) {
            $paymentMethodSubFormOptions = $this->getPaymentMethodSubFormOptions($paymentMethodSubForm);

            $builder->add(
                $paymentMethodSubForm->getName(),
                get_class($paymentMethodSubForm),
                ['select_options' => $options['select_options']] + $paymentMethodSubFormOptions,
            );
        }

        return $this;
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface $paymentMethodSubForm
     *
     * @return array<mixed>
     */
    protected function getPaymentMethodSubFormOptions(SubFormInterface $paymentMethodSubForm): array
    {
        $defaultOptions = [
            'property_path' => static::PAYMENT_PROPERTY_PATH . '.' . $paymentMethodSubForm->getPropertyPath(),
            'error_bubbling' => true,
            'label' => false,
        ];

        if (!$paymentMethodSubForm instanceof ExtraOptionsSubFormInterface) {
            return $defaultOptions;
        }

        return $defaultOptions + $paymentMethodSubForm->getExtraOptions();
    }

    /**
     * @return array<\Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface>
     */
    protected function getPaymentMethodSubForms(): array
    {
        $paymentMethodSubForms = [];

        $availablePaymentMethodsTransfer = $this->getFactory()->createPaymentMethodReader()
            ->getAvailablePaymentMethods();

        $availablePaymentMethodSubFormPlugins = $this->getFactory()->getPaymentMethodSubForms();
        $availablePaymentMethodSubFormPlugins = $this->filterOutNotAvailableForms(
            $availablePaymentMethodSubFormPlugins,
            $availablePaymentMethodsTransfer,
        );
        $availablePaymentMethodSubFormPlugins = $this->extendPaymentCollection(
            $availablePaymentMethodSubFormPlugins,
            $availablePaymentMethodsTransfer,
        );
        $filteredPaymentMethodSubFormPlugins = $this->filterPaymentMethodSubFormPlugins($availablePaymentMethodSubFormPlugins);

        foreach ($filteredPaymentMethodSubFormPlugins as $paymentMethodSubFormPlugin) {
            $paymentMethodSubForm = $this->createSubForm($paymentMethodSubFormPlugin);
            $paymentMethodSubForms[$paymentMethodSubForm->getName()] = $paymentMethodSubForm;
        }

        return $paymentMethodSubForms;
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection $paymentSubFormPluginCollection
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection
     */
    protected function extendPaymentCollection(
        SubFormPluginCollection $paymentSubFormPluginCollection,
        PaymentMethodsTransfer $paymentMethodsTransfer
    ): SubFormPluginCollection {
        $paymentCollectionExtenderPlugins = $this->getFactory()->getPaymentCollectionExtenderPlugins();

        foreach ($paymentCollectionExtenderPlugins as $paymentCollectionExtenderPlugin) {
            $paymentSubFormPluginCollection = $paymentCollectionExtenderPlugin->extendCollection(
                $paymentSubFormPluginCollection,
                $paymentMethodsTransfer,
            );
        }

        return $paymentSubFormPluginCollection;
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection $paymentMethodSubFormPlugins
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $availablePaymentMethodsTransfer
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection
     */
    protected function filterOutNotAvailableForms(
        SubFormPluginCollection $paymentMethodSubFormPlugins,
        PaymentMethodsTransfer $availablePaymentMethodsTransfer
    ): SubFormPluginCollection {
        $paymentMethodNames = $this->getAvailablePaymentMethodNames($availablePaymentMethodsTransfer);
        $paymentMethodNames = array_combine($paymentMethodNames, $paymentMethodNames);

        foreach ($paymentMethodSubFormPlugins as $key => $subFormPlugin) {
            $subFormName = $subFormPlugin->createSubForm()->getName();

            if (!isset($paymentMethodNames[$subFormName])) {
                unset($paymentMethodSubFormPlugins[$key]);
            }
        }

        $paymentMethodSubFormPlugins->reset();

        return $paymentMethodSubFormPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $availablePaymentMethodsTransfer
     *
     * @return array<string>
     */
    protected function getAvailablePaymentMethodNames(PaymentMethodsTransfer $availablePaymentMethodsTransfer): array
    {
        $paymentMethodNames = [];
        foreach ($availablePaymentMethodsTransfer->getMethods() as $paymentMethodTransfer) {
            $paymentMethodNames[] = $paymentMethodTransfer->getMethodName();
        }

        return $paymentMethodNames;
    }

    /**
     * @param array<\Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface> $paymentMethodSubForms
     *
     * @return array
     */
    protected function getPaymentMethodChoices(array $paymentMethodSubForms)
    {
        $choices = [];

        foreach ($paymentMethodSubForms as $paymentMethodSubForm) {
            $choices[$paymentMethodSubForm->getName()] = $paymentMethodSubForm->getPropertyPath();
        }

        return $choices;
    }

    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginInterface $paymentMethodSubForm
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    protected function createSubForm(SubFormPluginInterface $paymentMethodSubForm)
    {
        return $paymentMethodSubForm->createSubForm();
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                $validationGroups = [Constraint::DEFAULT_GROUP];

                $paymentSelectionFormData = $form->get(static::PAYMENT_SELECTION)->getData();
                if (is_string($paymentSelectionFormData)) {
                    $validationGroups[] = $paymentSelectionFormData;
                }

                return $validationGroups;
            },
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);

        $resolver->setRequired(SubFormInterface::OPTIONS_FIELD_NAME);
    }

    /**
     * @deprecated Exists for BC reasons. Will be removed in the next major release without replacement.
     *
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection $availablePaymentMethodSubFormPlugins
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection
     */
    protected function filterPaymentMethodSubFormPlugins(SubFormPluginCollection $availablePaymentMethodSubFormPlugins)
    {
        return $this->getFactory()
            ->createSubFormFilter()
            ->filterFormsCollection($availablePaymentMethodSubFormPlugins);
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\NotBlank
     */
    protected function createNotBlankConstraint(): NotBlank
    {
        return new NotBlank(['message' => static::VALIDATION_NOT_BLANK_MESSAGE]);
    }
}
