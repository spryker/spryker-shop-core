<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form;

use Exception;
use Generated\Shared\Transfer\PermissionTransfer;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageConfig getConfig()
 */
class CompanyRolePermissionConfigurationType extends AbstractType
{
    protected const FIELD_ID_COMPANY_ROLE = 'idCompanyRole';
    protected const FIELD_ID_PERMISSION = 'idPermission';

    public const OPTION_CONFIGURATION_SIGNATURE = 'OPTION_CONFIGURATION_SIGNATURE';

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'CompanyRolePermissionConfigurationType';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder = $this->addHiddenFieldIdCompanyRole($builder);
        $builder = $this->addHiddenIdPermission($builder);
        $this->addFieldsBySignature($builder, $builder->getData());
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    protected function addHiddenFieldIdCompanyRole(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_COMPANY_ROLE, HiddenType::class);

        return $builder;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    protected function addHiddenIdPermission(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_PERMISSION, HiddenType::class);

        return $builder;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param \Generated\Shared\Transfer\PermissionTransfer $permissionTransfer
     *
     * @return $this
     */
    protected function addFieldsBySignature(FormBuilderInterface $builder, PermissionTransfer $permissionTransfer)
    {
        foreach ($permissionTransfer->getConfigurationSignature() as $fieldName => $fieldType) {
            $this->addFieldBySignature($builder, $fieldName, $fieldType);
        }

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string $fieldName
     * @param string $fieldType
     *
     * @return void
     */
    protected function addFieldBySignature(FormBuilderInterface $builder, $fieldName, $fieldType)
    {
        $options = array_merge(
            $this->getSymfonyTypeOptionsByFieldType($fieldType),
            [
                'property_path' => PermissionTransfer::CONFIGURATION . '[' . $fieldName . ']',
                'required' => false,
            ]
        );

        $builder->add($fieldName, $this->getSymfonyTypeByFieldType($fieldType), $options);
    }

    /**
     * @param string $fieldType
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function getSymfonyTypeByFieldType(string $fieldType)
    {
        $fieldTypes = [
            ExecutablePermissionPluginInterface::CONFIG_FIELD_TYPE_INT => NumberType::class,
            ExecutablePermissionPluginInterface::CONFIG_FIELD_TYPE_STRING => TextType::class,
            ExecutablePermissionPluginInterface::CONFIG_FIELD_TYPE_STORE_MULTI_CURRENCY => CurrentStoreMultiCurrencyType::class,
        ];

        if (!isset($fieldTypes[$fieldType])) {
            throw new Exception('Required type is not defined. Please update your executable permission field type to Symfony type map');
        }

        return $fieldTypes[$fieldType];
    }

    /**
     * @param string $fieldType
     *
     * @return array
     */
    protected function getSymfonyTypeOptionsByFieldType(string $fieldType): array
    {
        $fieldTypeOptions = [
            ExecutablePermissionPluginInterface::CONFIG_FIELD_TYPE_STORE_MULTI_CURRENCY => [
                'label' => 'company_page.multi_currency_type.label',
            ],
        ];

        if (!isset($fieldTypeOptions[$fieldType])) {
            return [];
        }

        return $fieldTypeOptions[$fieldType];
    }
}
