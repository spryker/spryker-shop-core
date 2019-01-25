<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CategoryImageStorageWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CategoryImageStorageWidget\Dependency\CategoryImageStorageWidgetToCategoryImageStorageClientInterface;

class CategoryImageStorageWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CategoryImageStorageWidget\Dependency\CategoryImageStorageWidgetToCategoryImageStorageClientInterface
     */
    public function getCategoryImageStorageClient(): CategoryImageStorageWidgetToCategoryImageStorageClientInterface
    {
        return $this->getProvidedDependency(CategoryImageStorageWidgetDependencyProvider::CLIENT_CATEGORY_IMAGE_STORAGE);
    }
}
