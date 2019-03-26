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

/**
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 */
class CartTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    protected const TWIG_GLOBAL_VARIABLE_NAME_CART_QUANTITY = 'cartQuantity';

    /**
     * {@inheritdoc}
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
        $twig = $this->addCartQuantityGlobalVariable($twig);

        return $twig;
    }

    /**
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
