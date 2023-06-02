<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageConfig getConfig()
 */
class CompanyUserDeleteForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_ID_COMPANY_USER = 'idCompanyUser';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addIdCompanyUserField($builder);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompanyUserTransfer::class,
            'method' => Request::METHOD_POST,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCompanyUserField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_COMPANY_USER, HiddenType::class, [
            'constraints' => [
                new NotBlank(),
                new GreaterThanOrEqual(['value' => 1]),
            ],
        ]);

        return $this;
    }
}
