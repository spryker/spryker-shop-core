<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Controller;

use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchRequestTransfer;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ConfigurableBundlePage\ConfigurableBundlePageFactory getFactory()
 */
class ConfiguratorController extends AbstractController
{
    /**
     * @uses \SprykerShop\Yves\ConfigurableBundlePage\Plugin\Router\ConfigurableBundlePageRouteProviderPlugin::ROUTE_CONFIGURATOR_TEMPLATE_SELECTION
     */
    protected const ROUTE_CONFIGURATOR_TEMPLATE_SELECTION = 'configurable-bundle/configurator/template-selection';

    /**
     * @uses \SprykerShop\Yves\ConfigurableBundlePage\Plugin\Router\ConfigurableBundlePageRouteProviderPlugin::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE
     */
    protected const PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE = 'idConfigurableBundleTemplate';

    /**
     * @uses \SprykerShop\Yves\ConfigurableBundlePage\Plugin\Router\ConfigurableBundlePageRouteProviderPlugin::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT
     */
    protected const PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT = 'idConfigurableBundleTemplateSlot';

    /**
     * @uses \Spryker\Client\ConfigurableBundlePageSearch\Plugin\Elasticsearch\ResultFormatter\ConfigurableBundleTemplatePageSearchResultFormatterPlugin::NAME
     */
    protected const CONFIGURABLE_BUNDLE_TEMPLATE_PAGE_SEARCH_RESULT_FORMATTER_PLUGIN = 'ConfigurableBundleTemplatePageSearchResultFormatterPlugin';

    protected const GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND = 'configurable_bundle_page.template_not_found';
    protected const GLOSSARY_KEY_INVALID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_COMBINATION = 'configurable_bundle_page.invalid_template_slot_combination';

    /**
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function templateSelectionAction(): View
    {
        $response = $this->executeTemplateSelectionAction();

        return $this->view($response, [], '@ConfigurableBundlePage/views/template-selection/template-selection.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function slotsAction(Request $request): RedirectResponse
    {
        $idConfigurableBundleTemplate = $request->attributes->getInt(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE);
        $idConfigurableBundleTemplateSlot = $request->attributes->getInt(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT);

        $configurableBundleTemplateStorageTransfer = $this->getFactory()
            ->getConfigurableBundleStorageClient()
            ->findConfigurableBundleTemplateStorage($idConfigurableBundleTemplate);

        if (!$configurableBundleTemplateStorageTransfer) {
            $this->addErrorMessage(static::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND);

            return $this->redirectResponseInternal(static::ROUTE_CONFIGURATOR_TEMPLATE_SELECTION);
        }

        if (!$idConfigurableBundleTemplateSlot) {
            // initial page
        }

        $isTemplateSlotCombinationValid = $this->getFactory()
            ->createConfigurableBundleTemplateSlotCombinationValidator()
            ->validateConfigurableBundleTemplateSlotCombination($configurableBundleTemplateStorageTransfer, $idConfigurableBundleTemplateSlot);

        if (!$isTemplateSlotCombinationValid) {
            $this->addErrorMessage(static::GLOSSARY_KEY_INVALID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_COMBINATION);

            return $this->redirectResponseInternal(static::ROUTE_CONFIGURATOR_TEMPLATE_SELECTION);
        }

        // selected slot page
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
            'configurableBundleTemplates' => $formattedSearchResults[static::CONFIGURABLE_BUNDLE_TEMPLATE_PAGE_SEARCH_RESULT_FORMATTER_PLUGIN] ?? [],
        ];
    }
}
