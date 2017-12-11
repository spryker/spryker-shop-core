<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NavigationWidget\Plugin\ShopLayout;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\NavigationWidget\NavigationWidgetFactory;
use SprykerShop\Yves\ShopLayout\Dependency\Plugin\NavigationWidget\NavigationWidgetPluginInterface;

/**
 * Class NavigationWidgetPlugin
 *
 * @method NavigationWidgetFactory getFactory()
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
     * @return string
     */
    public function getNavigation($navigationKey)
    {
        $key = $navigationKey . '-' . $this->getLocale();

        if (!isset(static::$buffer[$key])) {
            $navigationTreeTransfer = $this->getFactory()->getNavigationStorageClient()->findNavigationTreeByKey($navigationKey, $this->getLocale());

            static::$buffer[$key] = $navigationTreeTransfer;
        }

        $navigationTreeTransfer = static::$buffer[$key];

        //TODO Fix the isActive
//        if (!$navigationTreeTransfer || !$navigationTreeTransfer()->getIsActive()) {
//            return '';
//        }


        return $navigationTreeTransfer;
    }
}
