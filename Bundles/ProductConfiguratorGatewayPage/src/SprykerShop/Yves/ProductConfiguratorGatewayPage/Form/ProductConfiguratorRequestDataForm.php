<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Form;

use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageFactory getFactory()
 * @method \SprykerShop\Yves\ProductConfiguratorGatewayPage\ProductConfiguratorGatewayPageConfig getConfig()
 */
class ProductConfiguratorRequestDataForm extends AbstractType
{
    public const FIELD_SOURCE_TYPE = ProductConfiguratorRequestDataTransfer::SOURCE_TYPE;
    public const OPTION_SOURCE_TYPE = self::FIELD_SOURCE_TYPE;

    protected const FIELD_CONFIGURATOR_KEY = ProductConfiguratorRequestDataTransfer::CONFIGURATOR_KEY;

    /**
     * @var string
     */
    protected const PRODUCT_CONFIGURATION_CSRF_TOKEN_ID = 'product_configuration';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SOURCE_TYPE_NOT_BLANK_MESSAGE = 'product_configurator_gateway_page.source_type_not_blank';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_CONFIGURATOR_KEY_NOT_BLANK_MESSAGE = 'product_configurator_gateway_page.configurator_key_not_blank';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addSourceTypeField($builder)
            ->addConfiguratorKeyField($builder)
            ->executeProductConfiguratorRequestDataFormExpanderStrategyPlugins($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductConfiguratorRequestDataTransfer::class,
            'csrf_token_id' => static::PRODUCT_CONFIGURATION_CSRF_TOKEN_ID,
        ]);

        $resolver->setRequired([static::OPTION_SOURCE_TYPE]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSourceTypeField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SOURCE_TYPE, HiddenType::class, [
            'required' => true,
            'constraints' => [
                new NotBlank(['message' => static::GLOSSARY_KEY_VALIDATION_SOURCE_TYPE_NOT_BLANK_MESSAGE]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addConfiguratorKeyField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CONFIGURATOR_KEY, HiddenType::class, [
            'constraints' => [
                new NotBlank(['message' => static::GLOSSARY_KEY_VALIDATION_SOURCE_TYPE_NOT_BLANK_MESSAGE]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $formBuilder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function executeProductConfiguratorRequestDataFormExpanderStrategyPlugins(FormBuilderInterface $formBuilder, array $options)
    {
        foreach ($this->getFactory()->getProductConfiguratorRequestDataFormExpanderStrategyPlugins() as $productConfiguratorRequestDataFormExpanderStrategyPlugin) {
            if ($productConfiguratorRequestDataFormExpanderStrategyPlugin->isApplicable($options)) {
                $productConfiguratorRequestDataFormExpanderStrategyPlugin->expand($formBuilder, $options);
            }
        }

        return $this;
    }
}
