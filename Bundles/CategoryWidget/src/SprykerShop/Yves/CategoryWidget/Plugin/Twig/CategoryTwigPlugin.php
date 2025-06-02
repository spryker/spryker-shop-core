<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CategoryWidget\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;

/**
 * @method \SprykerShop\Yves\CategoryWidget\CategoryWidgetFactory getFactory()
 */
class CategoryTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    /**
     * @var string
     */
    protected const TWIG_GLOBAL_VARIABLE_CATEGORIES = 'categories';

    /**
     * @var string
     */
    protected const SERVICE_LOCALE = 'locale';

    /**
     * @var string
     *
     * @uses \Spryker\Yves\Twig\Plugin\Console\TwigTemplateWarmingModeEventSubscriberPlugin::FLAG_TWIG_TEMPLATE_WARMING_MODE_ENABLED
     */
    protected const FLAG_TWIG_TEMPLATE_WARMING_MODE_ENABLED = 'FLAG_TWIG_TEMPLATE_WARMING_MODE_ENABLED';

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
        // In Twig template warming mode there's no need for any variable values.
        if (
            $container->has(static::FLAG_TWIG_TEMPLATE_WARMING_MODE_ENABLED)
            && $container->get(static::FLAG_TWIG_TEMPLATE_WARMING_MODE_ENABLED)
        ) {
            return $twig;
        }

        return $this->addGlobalTemplateVariables($twig, $container);
    }

    /**
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    protected function addGlobalTemplateVariables(Environment $twig, ContainerInterface $container): Environment
    {
        foreach ($this->getGlobalTemplateVariables($container) as $globalVariableName => $globalVariableValue) {
            $twig->addGlobal($globalVariableName, $globalVariableValue);
        }

        return $twig;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return array
     */
    protected function getGlobalTemplateVariables(ContainerInterface $container): array
    {
        return [
            static::TWIG_GLOBAL_VARIABLE_CATEGORIES => $this->getCategories($container),
        ];
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\CategoryNodeStorageTransfer>
     */
    protected function getCategories(ContainerInterface $container)
    {
        return $this
            ->getFactory()
            ->getCategoryStorageClient()
            ->getCategories($container->get(static::SERVICE_LOCALE), $this->getFactory()->getStoreClient()->getCurrentStore()->getNameOrFail());
    }
}
