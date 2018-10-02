<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NavigationWidget\Widget;

use Generated\Shared\Transfer\NavigationStorageTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\NavigationWidget\NavigationWidgetFactory getFactory()
 */
class NavigationWidget extends AbstractWidget
{
    /**
     * @var array
     */
    protected static $buffer = [];

    /**
     * @param string $navigationKey
     * @param string $template
     */
    public function __construct(string $navigationKey, string $template)
    {
        $this->addParameter('navigationTree', $this->getNavigation($navigationKey))
            ->addParameter('template', $template);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'NavigationWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@NavigationWidget/views/navigation/navigation.twig';
    }

    /**
     * @param string $navigationKey
     *
     * @return \Generated\Shared\Transfer\NavigationStorageTransfer|null
     */
    protected function getNavigation(string $navigationKey): ?NavigationStorageTransfer
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
