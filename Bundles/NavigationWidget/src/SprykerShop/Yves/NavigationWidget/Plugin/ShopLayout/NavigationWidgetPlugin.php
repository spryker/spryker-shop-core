<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NavigationWidget\Plugin\ShopLayout;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShopLayout\Dependency\Plugin\NavigationWidget\NavigationWidgetPluginInterface;

/**
 * Class NavigationWidgetPlugin
 *
 * @method \SprykerShop\Yves\NavigationWidget\NavigationWidgetFactory getFactory()
 */
class NavigationWidgetPlugin extends AbstractWidgetPlugin implements NavigationWidgetPluginInterface
{
    /**
     * @var array
     */
    protected static $buffer = [];

    /**
     * @param string $navigationKey
     * @param string $template
     *
     * @return void
     */
    public function initialize($navigationKey, $template): void
    {
        $this->addParameter('navigationTree', $this->getNavigation($navigationKey))
            ->addParameter('template', $template);
    }

    /**
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@NavigationWidget/_navigation/_main.twig';
    }

    /**
     * @param string $navigationKey
     *
     * @return \Generated\Shared\Transfer\NavigationStorageTransfer|null
     */
    public function getNavigation($navigationKey)
    {
        $key = $navigationKey . '-' . $this->getLocale();

        if (!isset(static::$buffer[$key])) {
            $navigationStorageTransfer = $this->getFactory()->getNavigationStorageClient()->findNavigationTreeByKey($navigationKey, $this->getLocale());

            static::$buffer[$key] = $navigationStorageTransfer;
        }

        $navigationStorageTransfer = static::$buffer[$key];

        if (!$navigationStorageTransfer || !$navigationStorageTransfer->getIsActive()) {
            return null;
        }

        return $navigationStorageTransfer;
    }
}
