<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Plugin;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\FormRendererEngineInterface;
use Symfony\Component\Form\FormRendererInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Twig\Environment;
use Twig\RuntimeLoader\FactoryRuntimeLoader;

/**
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationConfig getConfig()
 */
class TwigFormRuntimeLoaderPlugin extends AbstractPlugin implements TwigPluginInterface
{
    protected const SERVICE_FORM_CSRF_PROVIDER = 'form.csrf_provider';

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
        $twig->addRuntimeLoader($this->createFactoryRuntimeLoader($twig, $container));

        return $twig;
    }

    /**
     * @return string[]
     */
    protected function getTwigTemplateFileNames(): array
    {
        return $this->getConfig()->getFormThemes();
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Symfony\Component\Form\FormRendererEngineInterface
     */
    protected function createTwigRendererEngine(Environment $twig): FormRendererEngineInterface
    {
        return new TwigRendererEngine($this->getTwigTemplateFileNames(), $twig);
    }

    /**
     * @param \Twig\Environment $twig
     * @param \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface $csrfTokenManager
     *
     * @return \Symfony\Component\Form\FormRendererInterface
     */
    protected function createFormRenderer(Environment $twig, CsrfTokenManagerInterface $csrfTokenManager): FormRendererInterface
    {
        return new FormRenderer($this->createTwigRendererEngine($twig), $csrfTokenManager);
    }

    /**
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\RuntimeLoader\FactoryRuntimeLoader
     */
    protected function createFactoryRuntimeLoader(Environment $twig, ContainerInterface $container): FactoryRuntimeLoader
    {
        $formRendererCallback = function () use ($twig, $container) {
            return $this->createFormRenderer($twig, $container->get(static::SERVICE_FORM_CSRF_PROVIDER));
        };

        $loadersMap = [];
        $loadersMap[FormRenderer::class] = $formRendererCallback;
        if (class_exists(TwigRenderer::class)) {
            $loadersMap[TwigRenderer::class] = $formRendererCallback;
        }

        return new FactoryRuntimeLoader($loadersMap);
    }
}
