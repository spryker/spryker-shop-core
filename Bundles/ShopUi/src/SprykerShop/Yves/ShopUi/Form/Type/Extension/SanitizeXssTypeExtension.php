<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Form\Type\Extension;

use SprykerShop\Yves\ShopUi\Dependency\Service\ShopUiToUtilSanitizeXssServiceInterface;
use SprykerShop\Yves\ShopUi\Form\Listener\SanitizeXssListener;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SanitizeXssTypeExtension extends AbstractTypeExtension
{
    /**
     * @var string
     */
    public const OPTION_SANITIZE_XSS = 'sanitize_xss';

    /**
     * @var string
     */
    public const OPTION_ALLOWED_ATTRIBUTES = 'allowed_attributes';

    /**
     * @var string
     */
    public const OPTION_ALLOWED_HTML_TAGS = 'allowed_html_tags';

    /**
     * @var \SprykerShop\Yves\ShopUi\Dependency\Service\ShopUiToUtilSanitizeXssServiceInterface
     */
    protected ShopUiToUtilSanitizeXssServiceInterface $utilSanitizeXssService;

    /**
     * @param \SprykerShop\Yves\ShopUi\Dependency\Service\ShopUiToUtilSanitizeXssServiceInterface $utilSanitizeXssService
     */
    public function __construct(ShopUiToUtilSanitizeXssServiceInterface $utilSanitizeXssService)
    {
        $this->utilSanitizeXssService = $utilSanitizeXssService;
    }

    /**
     * @return list<string>
     */
    public static function getExtendedTypes(): iterable
    {
        return [
            TextType::class,
        ];
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefined([
                static::OPTION_SANITIZE_XSS,
                static::OPTION_ALLOWED_ATTRIBUTES,
                static::OPTION_ALLOWED_HTML_TAGS,
            ])
            ->setDefaults([
                static::OPTION_SANITIZE_XSS => false,
                static::OPTION_ALLOWED_ATTRIBUTES => [],
                static::OPTION_ALLOWED_HTML_TAGS => [],
            ])
            ->setAllowedTypes(static::OPTION_SANITIZE_XSS, 'bool')
            ->setAllowedTypes(static::OPTION_ALLOWED_ATTRIBUTES, 'array')
            ->setAllowedTypes(static::OPTION_ALLOWED_HTML_TAGS, 'array');
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options[static::OPTION_SANITIZE_XSS]) {
            $builder->addEventSubscriber(new SanitizeXssListener($this->utilSanitizeXssService));
        }
    }
}
