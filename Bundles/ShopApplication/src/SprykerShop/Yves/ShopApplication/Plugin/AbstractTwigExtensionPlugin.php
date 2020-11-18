<?php
// phpcs:ignoreFile

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Plugin;

use Twig\Environment;
use Twig\Extension\GlobalsInterface;

if (Environment::MAJOR_VERSION < 3) {
    /**
     * @method \Spryker\Yves\Kernel\AbstractFactory getFactory()
     */
    abstract class AbstractTwigExtensionPlugin extends BaseAbstractTwigExtensionPlugin implements GlobalsInterface
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
    abstract class AbstractTwigExtensionPlugin extends BaseAbstractTwigExtensionPlugin implements GlobalsInterface
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
