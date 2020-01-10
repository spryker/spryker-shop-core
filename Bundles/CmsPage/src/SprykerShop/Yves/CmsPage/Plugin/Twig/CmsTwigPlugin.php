<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsPage\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \SprykerShop\Yves\CmsPage\CmsPageFactory getFactory()
 */
class CmsTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    protected const CMS_PREFIX_KEY = 'generated.cms';
    protected const TWIG_FUNCTION_SPY_CMS = 'spyCms';
    protected const SERVICE_TRANSLATOR = 'translator';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        return $this->registerCmsTwigFunction($twig, $container);
    }

    /**
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    protected function registerCmsTwigFunction(Environment $twig, ContainerInterface $container): Environment
    {
        $twig->addFunction(
            new TwigFunction(static::TWIG_FUNCTION_SPY_CMS, function (array $context, $identifier) use ($container) {
                return $this->getTranslation($identifier, $context, $container);
            }, ['needs_context' => true])
        );

        return $twig;
    }

    /**
     * @param string $identifier
     * @param array $context
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return string
     */
    protected function getTranslation(string $identifier, array $context, ContainerInterface $container): string
    {
        $placeholders = $context['_view']['placeholders'];

        $translation = '';
        if (isset($placeholders[$identifier])) {
            $translation = $placeholders[$identifier];
        }

        if ($this->isGlossaryKey($translation)) {
            $translator = $this->getTranslator($container);
            $translation = $translator->trans($translation);
        }

        if ($this->isGlossaryKey($translation)) {
            $translation = '';
        }

        return $translation;
    }

    /**
     * @param string $translation
     *
     * @return bool
     */
    protected function isGlossaryKey(string $translation): bool
    {
        return strpos($translation, static::CMS_PREFIX_KEY) === 0;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Translation\TranslatorInterface
     */
    protected function getTranslator(ContainerInterface $container): TranslatorInterface
    {
        return $container->get(static::SERVICE_TRANSLATOR);
    }
}
