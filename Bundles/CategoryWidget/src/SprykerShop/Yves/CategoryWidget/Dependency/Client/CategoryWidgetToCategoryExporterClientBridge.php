<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CategoryWidget\Dependency\Client;

class CategoryWidgetToCategoryExporterClientBridge implements CategoryWidgetToCategoryExporterClientInterface
{
    /**
     * @var \Spryker\Client\CategoryExporter\CategoryExporterClientInterface
     */
    protected $categoryExporterClient;

    /**
     * @param \Spryker\Client\CategoryExporter\CategoryExporterClientInterface $categoryExporterClient
     */
    public function __construct($categoryExporterClient)
    {
        $this->categoryExporterClient = $categoryExporterClient;
    }

    /**
     * @param string $locale
     *
     * @return array
     */
    public function getNavigationCategories($locale)
    {
        return $this->categoryExporterClient->getNavigationCategories($locale);
    }
}
