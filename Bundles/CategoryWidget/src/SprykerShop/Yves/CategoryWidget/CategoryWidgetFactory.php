<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CategoryWidget;

use Spryker\Client\CategoryStorage\CategoryStorageClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;

class CategoryWidgetFactory extends AbstractFactory
{

    /**
     * @return CategoryStorageClientInterface
     */
    public function getCategoryStorageClient()
    {
        return $this->getProvidedDependency(CategoryWidgetDependencyProvider::CLIENT_CATEGORY_STORAGE);
    }
}
