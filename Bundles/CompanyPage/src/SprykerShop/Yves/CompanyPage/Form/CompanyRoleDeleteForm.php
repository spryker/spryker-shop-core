<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form;

use Generated\Shared\Transfer\CompanyRoleTransfer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageConfig getConfig()
 */
class CompanyRoleDeleteForm extends AbstractType
{
    public const FIELD_ID_COMPANY_ROLE = 'idCompanyRole';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addIdCompanyRoleField($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompanyRoleTransfer::class,
            'method' => Request::METHOD_POST,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCompanyRoleField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_COMPANY_ROLE, HiddenType::class, [
            'constraints' => [
                new NotBlank(),
                new Positive(),
            ],
        ]);

        return $this;
    }
}
