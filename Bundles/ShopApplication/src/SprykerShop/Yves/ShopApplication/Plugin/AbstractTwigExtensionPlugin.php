<?php
// phpcs:ignoreFile

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Plugin;

use Spryker\Shared\Twig\TwigGlobalsInterface;
use Twig\Environment;

if (Environment::MAJOR_VERSION == 1) {
    /**
     * @method \Spryker\Yves\Kernel\AbstractFactory getFactory()
     */
    abstract class AbstractTwigExtensionPlugin extends BaseAbstractTwigExtensionPlugin
    {
        /**
         * Specification:
         * - Returns a list of global variables to add to the existing list.
         *
         * @api
         *
         * @return array An array of global variables
         */
        public function getGlobals()
        {
            return [];
        }
    }
} elseif (Environment::MAJOR_VERSION == 2) {
    /**
     * @method \Spryker\Yves\Kernel\AbstractFactory getFactory()
     */
    abstract class AbstractTwigExtensionPlugin extends BaseAbstractTwigExtensionPlugin implements TwigGlobalsInterface
    {
        /**
         * Specification:
         * - Returns a list of global variables to add to the existing list.
         *
         * @api
         *
         * @return array An array of global variables
         */
        public function getGlobals()
        {
            return [];
        }
    }
} else {
    /**
     * @method \Spryker\Yves\Kernel\AbstractFactory getFactory()
     */
    abstract class AbstractTwigExtensionPlugin extends BaseAbstractTwigExtensionPlugin implements TwigGlobalsInterface
    {
        /**
         * Specification:
         * - Returns a list of global variables to add to the existing list.
         *
         * @api
         *
         * @return array An array of global variables
         */
        public function getGlobals(): array
        {
            return [];
        }
    }
}
