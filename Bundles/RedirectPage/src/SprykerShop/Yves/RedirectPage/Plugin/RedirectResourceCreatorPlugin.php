<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\RedirectPage\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ShopRouter\Dependency\Plugin\ResourceCreatorPluginInterface;

/**
 * @method \SprykerShop\Yves\RedirectPage\RedirectPageFactory getFactory()
 */
class RedirectResourceCreatorPlugin extends AbstractPlugin implements ResourceCreatorPluginInterface
{
    /**
     * @return string
     */
    public function getType()
    {
        return 'redirect';
    }

    /**
     * @return string
     */
    public function getModuleName()
    {
        return 'RedirectPage';
    }

    /**
     * @return string
     */
    public function getControllerName()
    {
        return 'Redirect';
    }

    /**
     * @return string
     */
    public function getActionName()
    {
        return 'redirect';
    }

    /**
     * @return array
     */
    public function mergeResourceData(array $data)
    {
        return [
            'meta' => $data,
        ];
    }
}
