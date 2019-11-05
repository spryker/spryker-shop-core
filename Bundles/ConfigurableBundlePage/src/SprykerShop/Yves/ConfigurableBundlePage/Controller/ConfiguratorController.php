<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Controller;

use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchRequestTransfer;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;

/**
 * @method \SprykerShop\Yves\ConfigurableBundlePage\ConfigurableBundlePageFactory getFactory()
 */
class ConfiguratorController extends AbstractController
{
    /**
     * @uses \Spryker\Client\ConfigurableBundlePageSearch\Plugin\Elasticsearch\ResultFormatter\ConfigurableBundleTemplatePageSearchResultFormatterPlugin::NAME
     */
    protected const FORMATTED_RESULT_KEY = 'ConfigurableBundleTemplateCollection';

    /**
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function templateSelectionAction(): View
    {
        $response = $this->executeTemplateSelectionAction();

        return $this->view($response, [], '@ConfigurableBundlePage/views/template-selection/template-selection.twig');
    }

    /**
     * @return array
     */
    protected function executeTemplateSelectionAction(): array
    {
        $formattedSearchResults = $this->getFactory()
            ->getConfigurableBundlePageSearchClient()
            ->searchConfigurableBundleTemplates(
                new ConfigurableBundleTemplatePageSearchRequestTransfer()
            );

        return [
            'configurableBundleTemplates' => $formattedSearchResults[static::FORMATTED_RESULT_KEY] ?? [],
        ];
    }
}
