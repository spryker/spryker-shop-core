<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 */
class CartTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    /**
     * @deprecated Use {@link FUNCTION_NAME_GET_CART_QUANTITY} instead.
     *
     * @var string
     */
    protected const TWIG_GLOBAL_VARIABLE_NAME_CART_QUANTITY = 'cartQuantity';

    /**
     * @var string
     */
    protected const FUNCTION_NAME_GET_CART_QUANTITY = 'getCartQuantity';

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
        $twig = $this->addTwigFunctions($twig);

        // In Twig template warming mode there's no need for any variable values.
        if (
            $container->has(static::FLAG_TWIG_TEMPLATE_WARMING_MODE_ENABLED)
            && $container->get(static::FLAG_TWIG_TEMPLATE_WARMING_MODE_ENABLED)
        ) {
            return $twig;
        }

        $twig = $this->addCartQuantityGlobalVariable($twig);

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    protected function addTwigFunctions(Environment $twig): Environment
    {
        $twig->addFunction($this->getCartQuantityFunction());

        return $twig;
    }

    /**
     * @return \Twig\TwigFunction
     */
    protected function getCartQuantityFunction(): TwigFunction
    {
        return new TwigFunction(static::FUNCTION_NAME_GET_CART_QUANTITY, function () {
            return $this->getFactory()
                ->getCartClient()
                ->getItemCount();
        });
    }

    /**
     * @deprecated Use {@link getCartQuantityFunction()} instead.
     *
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    protected function addCartQuantityGlobalVariable(Environment $twig): Environment
    {
        $quantity = $this->getFactory()
            ->getCartClient()
            ->getItemCount();

        $twig->addGlobal(static::TWIG_GLOBAL_VARIABLE_NAME_CART_QUANTITY, $quantity);

        return $twig;
    }
}
