<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Controller;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchRequestTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageRequestTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer;
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function slotsAction(Request $request)
    {
        $response = $this->executeSlotsAction($request);

        if ($response instanceof RedirectResponse) {
            return $response;
        }

        return $this->view($response, [], '@ConfigurableBundlePage/views/slots/slots.twig');
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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeSlotsAction(Request $request)
    {
        $idConfigurableBundleTemplate = $request->attributes->getInt(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE);
        $idConfigurableBundleTemplateSlot = $request->attributes->getInt(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT);

        $configurableBundleTemplateStorageTransfer = $this->findConfigurableBundleTemplateStorage($idConfigurableBundleTemplate, $idConfigurableBundleTemplateSlot);

        if (!$configurableBundleTemplateStorageTransfer) {
            return $this->redirectResponseInternal(static::ROUTE_CONFIGURATOR_TEMPLATE_SELECTION);
        }

        $response = [
            'form' => $this->getFactory()->getConfiguratorStateForm()->handleRequest($request),
            'configurableBundleTemplateStorage' => $configurableBundleTemplateStorageTransfer,
        ];

        if (!$idConfigurableBundleTemplateSlot) {
            return $response;
        }

        $response['selectedSlotId'] = $idConfigurableBundleTemplateSlot;
        $response['productConcreteCriteriaFilter'] = $this->createProductConcreteCriteriaFilterTransfer(
            $configurableBundleTemplateStorageTransfer,
            $idConfigurableBundleTemplateSlot
        );

        return $response;
    }

    /**
     * @param int $idConfigurableBundleTemplate
     * @param int|null $idConfigurableBundleTemplateSlot
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer|null
     */
    protected function findConfigurableBundleTemplateStorage(int $idConfigurableBundleTemplate, ?int $idConfigurableBundleTemplateSlot): ?ConfigurableBundleTemplateStorageTransfer
    {
        $configurableBundleTemplateStorageRequestTransfer = (new ConfigurableBundleTemplateStorageRequestTransfer())
            ->setIdConfigurableBundleTemplate($idConfigurableBundleTemplate)
            ->setIdConfigurableBundleTemplateSlot($idConfigurableBundleTemplateSlot);

        $configurableBundleTemplateStorageResponseTransfer = $this->getFactory()
            ->createConfigurableBundleTemplateStorageReader()
            ->getConfigurableBundleTemplateStorage($configurableBundleTemplateStorageRequestTransfer);

        if (!$configurableBundleTemplateStorageResponseTransfer->getIsSuccessful()) {
            $this->handleErrors($configurableBundleTemplateStorageResponseTransfer->getMessages());

            return null;
        }

        return $configurableBundleTemplateStorageResponseTransfer->getConfigurableBundleTemplateStorage();
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     * @param int $idConfigurableBundleTemplateSlot
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer
     */
    protected function createProductConcreteCriteriaFilterTransfer(ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer, int $idConfigurableBundleTemplateSlot): ProductConcreteCriteriaFilterTransfer
    {
        /** @var \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer $configurableBundleTemplateSlotStorageTransfer */
        $configurableBundleTemplateSlotStorageTransfer = $configurableBundleTemplateStorageTransfer->getSlots()->offsetGet($idConfigurableBundleTemplateSlot);

        return (new ProductConcreteCriteriaFilterTransfer())->setRequestParams([
            'idProductList' => $configurableBundleTemplateSlotStorageTransfer->getIdProductList(),
        ]);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[] $messageTransfers
     *
     * @return void
     */
    protected function handleErrors(ArrayObject $messageTransfers): void
    {
        foreach ($messageTransfers as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getValue());
        }
    }
}
