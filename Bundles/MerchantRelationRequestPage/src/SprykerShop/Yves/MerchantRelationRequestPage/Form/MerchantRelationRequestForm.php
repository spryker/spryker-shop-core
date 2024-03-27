<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Form;

use ArrayObject;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \SprykerShop\Yves\MerchantRelationRequestPage\MerchantRelationRequestPageFactory getFactory()
 * @method \SprykerShop\Yves\MerchantRelationRequestPage\MerchantRelationRequestPageConfig getConfig()
 */
class MerchantRelationRequestForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_BUSINESS_UNIT_CHOICES = 'option_business_unit_choices';

    /**
     * @var string
     */
    public const OPTION_BUSINESS_UNITS = 'option_business_units';

    /**
     * @var string
     */
    public const OPTION_MERCHANT_CHOICES = 'option_merchant_choices';

    /**
     * @var string
     */
    public const OPTION_MERCHANTS = 'option_merchants';

    /**
     * @var string
     */
    public const OPTION_SELECTED_MERCHANT_REFERENCE = 'option_selected_merchant_reference';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => MerchantRelationRequestTransfer::class,
            'label' => false,
        ]);
        $resolver->setRequired([
            static::OPTION_BUSINESS_UNIT_CHOICES,
            static::OPTION_BUSINESS_UNITS,
            static::OPTION_MERCHANT_CHOICES,
            static::OPTION_MERCHANTS,
            static::OPTION_SELECTED_MERCHANT_REFERENCE,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addMerchantSubForm($builder, $options);
        $this->addOwnerCompanyBusinessUnitSubForm($builder, $options);
        $this->addAssigneeCompanyBusinessUnitsField($builder, $options);
        $this->addRequestNoteField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addMerchantSubForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            MerchantRelationRequestTransfer::MERCHANT,
            MerchantSubForm::class,
            [
                static::OPTION_SELECTED_MERCHANT_REFERENCE => $options[static::OPTION_SELECTED_MERCHANT_REFERENCE],
                static::OPTION_MERCHANT_CHOICES => $options[static::OPTION_MERCHANT_CHOICES],
            ],
        );

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($options): void {
            $this->getFactory()->createMerchantRelationRequestFormHydrator()->hydrateMerchant($event, $options);
        });

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addOwnerCompanyBusinessUnitSubForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            MerchantRelationRequestTransfer::OWNER_COMPANY_BUSINESS_UNIT,
            OwnerCompanyBusinessUnitSubForm::class,
            [
                static::OPTION_BUSINESS_UNIT_CHOICES => $options[static::OPTION_BUSINESS_UNIT_CHOICES],
            ],
        );

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($options): void {
            $this->getFactory()
                ->createMerchantRelationRequestFormHydrator()
                ->hydrateOwnerCompanyBusinessUnit($event, $options);
        });

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addAssigneeCompanyBusinessUnitsField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(MerchantRelationRequestTransfer::ASSIGNEE_COMPANY_BUSINESS_UNITS, ChoiceType::class, [
            'choices' => array_flip($options[static::OPTION_BUSINESS_UNIT_CHOICES]),
            'expanded' => true,
            'required' => true,
            'label' => 'merchant_relation_request_page.merchant_relation_request.business_units',
            'multiple' => true,
            'constraints' => $this->createAssigneeCompanyBusinessUnitsConstraints(),
        ]);

        $builder
            ->get(MerchantRelationRequestTransfer::ASSIGNEE_COMPANY_BUSINESS_UNITS)
            ->addModelTransformer($this->getFactory()->createCompanyBusinessUnitTransformer()->transformCollection());

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($options): void {
            $this->getFactory()
                ->createMerchantRelationRequestFormHydrator()
                ->hydrateAssigneeCompanyBusinessUnits($event, $options);
        });

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addRequestNoteField(FormBuilderInterface $builder)
    {
        $builder->add(MerchantRelationRequestTransfer::REQUEST_NOTE, TextareaType::class, [
            'label' => 'merchant_relation_request_page.merchant_relation_request.request_note',
            'required' => false,
            'sanitize_xss' => true,
            'constraints' => [
                new Length(['min' => 1, 'max' => 5000]),
            ],
        ]);

        return $this;
    }

    /**
     * @return list<\Symfony\Component\Validator\Constraints\Callback>
     */
    protected function createAssigneeCompanyBusinessUnitsConstraints(): array
    {
        $constraints = [];

        $constraints[] = new Callback([
            'callback' => function (ArrayObject $assigneeCompanyBusinessUnits, ExecutionContextInterface $context): void {
                if (!$assigneeCompanyBusinessUnits->count()) {
                    $context->addViolation('merchant_relation_request_page.merchant_relation_request.empty_business_units.error');
                }
            },
        ]);

        return $constraints;
    }
}
