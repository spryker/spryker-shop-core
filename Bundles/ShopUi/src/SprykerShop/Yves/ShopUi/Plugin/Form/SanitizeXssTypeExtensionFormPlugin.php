<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Plugin\Form;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\FormExtension\Dependency\Plugin\FormPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\Form\FormFactoryBuilderInterface;

/**
 * @method \SprykerShop\Yves\ShopUi\ShopUiFactory getFactory()
 */
class SanitizeXssTypeExtensionFormPlugin extends AbstractPlugin implements FormPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds {@link \SprykerShop\Yves\ShopUi\Form\Type\Extension\SanitizeXssTypeExtension}.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormFactoryBuilderInterface $formFactoryBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Form\FormFactoryBuilderInterface
     */
    public function extend(FormFactoryBuilderInterface $formFactoryBuilder, ContainerInterface $container): FormFactoryBuilderInterface
    {
        return $formFactoryBuilder->addTypeExtension($this->getFactory()->createSanitizeXssTypeExtension());
    }
}
