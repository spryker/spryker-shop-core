<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CompanyUserInvitationForm extends AbstractType
{
    const FIELD_INVITATIONS_LIST = 'invitations_list';

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'companyUserInvitationForm';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_INVITATIONS_LIST, FileType::class, [
            'label' => 'company.user.invitation.name',
            'required' => true,
            'constraints' => [
                new NotBlank(),
            ],
        ]);
    }
}
