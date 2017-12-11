<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CategoryWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CategoryWidget\Dependency\Client\CategoryWidgetToCategoryExporterClientInterface;

class CategoryWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CategoryWidget\Dependency\Client\CategoryWidgetToCategoryExporterClientInterface
     */
    public function getCategoryExporterClient(): CategoryWidgetToCategoryExporterClientInterface
    {
        return $this->getProvidedDependency(CategoryWidgetDependencyProvider::CLIENT_CATEGORY_EXPORTER);
    }
}
