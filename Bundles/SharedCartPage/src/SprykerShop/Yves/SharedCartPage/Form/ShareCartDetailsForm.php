<?php

namespace SprykerShop\Yves\SharedCartPage\Form;

use Generated\Shared\Transfer\ShareDetailTransfer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ShareCartDetailsForm extends AbstractType
{
    public const FORM_NAME = 'shareCartDetailsForm';

    public const FIELD_ID_QUOTE_COMPANY_USER = 'idQuoteCompanyUser';
    public const FIELD_ID_COMPANY_USER = 'idCompanyUser';
    public const FIELD_CUSTOMER_NAME = 'customerName';
    public const FIELD_QUOTE_PERMISSION_GROUP = 'quotePermissionGroup';

    public const OPTION_CUSTOMERS = 'OPTION_CUSTOMERS';
    public const OPTION_PERMISSION_GROUPS = 'OPTION_PERMISSION_GROUPS';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return self::FORM_NAME;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            static::FIELD_ID_QUOTE_COMPANY_USER,
            static::FIELD_ID_COMPANY_USER,
            static::FIELD_CUSTOMER_NAME,
            static::FIELD_QUOTE_PERMISSION_GROUP,
        ])->setDefaults([
            'data_class' => ShareDetailTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addIdQuoteCompanyUserField($builder)
            ->addIdCompanyUserField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \SprykerShop\Yves\SharedCartPage\Form\ShareCartDetailsForm
     */
    protected function addIdQuoteCompanyUserField(FormBuilderInterface $builder): self
    {
        $builder->add(static::FIELD_ID_QUOTE_COMPANY_USER, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addIdCompanyUserField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_ID_COMPANY_USER, ChoiceType::class, [
            'choices' => array_flip($options[static::OPTION_CUSTOMERS]),
            'choices_as_values' => true,
            'expanded' => false,
            'required' => true,
            'placeholder' => 'shared_cart.form.select_customer',
            'constraints' => [
                new NotBlank(),
            ],
            'label' => 'shared_cart.form.customer',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addQuotePermissionGroupIdQuoteIdField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_QUOTE_PERMISSION_GROUP, ChoiceType::class, [
            'choices' => array_flip($options[static::OPTION_PERMISSION_GROUPS]),
            'choices_as_values' => true,
            'expanded' => false,
            'required' => true,
            'constraints' => [
                new NotBlank(),
            ],
            'label' => 'shared_cart.form.select_permissions',
        ]);

        return $this;
    }

    // TODO: Finish sub-form.
}
