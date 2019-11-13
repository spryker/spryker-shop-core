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
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ConfigurableBundlePage\Form\ConfiguratorStateForm;
use SprykerShop\Yves\ConfigurableBundlePage\Form\SlotStateForm;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
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
     * @uses \SprykerShop\Yves\ConfigurableBundlePage\Plugin\Router\ConfigurableBundlePageRouteProviderPlugin::ROUTE_CONFIGURATOR_SLOTS
     */
    protected const ROUTE_CONFIGURATOR_SLOTS = 'configurable-bundle/configurator/slots';

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
     * @uses \Spryker\Client\ProductListSearch\Plugin\Search\ProductListQueryExpanderPlugin::REQUEST_PARAM_ID_PRODUCT_LIST
     */
    protected const REQUEST_PARAM_ID_PRODUCT_LIST = ProductListTransfer::ID_PRODUCT_LIST;

    /**
     * @uses \Spryker\Client\ProductListSearch\Plugin\Search\ProductListQueryExpanderPlugin::REQUEST_PARAM_ITEMS_PER_PAGE
     */
    protected const REQUEST_PARAM_ITEMS_PER_PAGE = 'itemsPerPage';

    /**
     * @see \SprykerShop\Yves\CatalogPage\CatalogPageConfig::CATALOG_PAGE_LIMIT
     */
    protected const REQUEST_PARAM_ITEMS_PER_PAGE_VALUE = 10000;

    protected const GLOSSARY_KEY_CONFIGURATOR_SUMMARY_PAGE_LOCKED = 'configurable_bundle_page.configurator.summary_page_locked';

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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function summaryAction(Request $request)
    {
        $response = $this->executeSummaryAction($request);

        if ($response instanceof RedirectResponse) {
            return $response;
        }

        return $this->view($response, [], '@ConfigurableBundlePage/views/summary/summary.twig');
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

        $form = $this->getFactory()->getConfiguratorStateForm()->handleRequest($request);

        $response = [
            'form' => $form,
            'configurableBundleTemplateStorage' => $configurableBundleTemplateStorageTransfer,
        ];

        if (!$idConfigurableBundleTemplateSlot) {
            return $response;
        }

        $response = array_merge($response, [
            'selectedSlotId' => $idConfigurableBundleTemplateSlot,
            'selectedProductConcrete' => $this->findSelectedProductConcreteForSlot($form, $idConfigurableBundleTemplateSlot),
            'productConcreteCriteriaFilter' => $this->createProductConcreteCriteriaFilterTransfer($configurableBundleTemplateStorageTransfer, $idConfigurableBundleTemplateSlot),
            'isSummaryPageUnlocked' => $this->isSummaryPageUnlocked($form),
        ]);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeSummaryAction(Request $request)
    {
        $idConfigurableBundleTemplate = $request->attributes->getInt(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE);
        $configurableBundleTemplateStorageTransfer = $this->findConfigurableBundleTemplateStorage($idConfigurableBundleTemplate);

        if (!$configurableBundleTemplateStorageTransfer) {
            return $this->redirectResponseInternal(static::ROUTE_CONFIGURATOR_TEMPLATE_SELECTION);
        }

        $form = $this->getFactory()->getConfiguratorStateForm()->handleRequest($request);

        if (!$this->isSummaryPageUnlocked($form)) {
            $this->addErrorMessage(static::GLOSSARY_KEY_CONFIGURATOR_SUMMARY_PAGE_LOCKED);

            return $this->redirectResponseInternal(static::ROUTE_CONFIGURATOR_SLOTS, [
                static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE => $request->attributes->getInt(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE),
            ]);
        }

        $productConcreteTransfers = $this->getFactory()
            ->getConfigurableBundleStorageClient()
            ->getProductConcreteStoragesBySkusForCurrentLocale($this->extractProductConcreteSkus($form));

        return [
            'form' => $form,
            'configurableBundleTemplateStorage' => $configurableBundleTemplateStorageTransfer,
            'products' => $productConcreteTransfers,
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool
     */
    protected function isSummaryPageUnlocked(FormInterface $form): bool
    {
        $slotStateFormsData = $form->getData()[ConfiguratorStateForm::FILED_SLOTS] ?? [];

        foreach ($slotStateFormsData as $slotStateFormData) {
            $sku = $slotStateFormData[SlotStateForm::FILED_SKU] ?? null;

            if ($sku) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param int $idConfigurableBundleTemplate
     * @param int|null $idConfigurableBundleTemplateSlot
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer|null
     */
    protected function findConfigurableBundleTemplateStorage(int $idConfigurableBundleTemplate, ?int $idConfigurableBundleTemplateSlot = null): ?ConfigurableBundleTemplateStorageTransfer
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
            static::REQUEST_PARAM_ID_PRODUCT_LIST => $configurableBundleTemplateSlotStorageTransfer->getIdProductList(),
            static::REQUEST_PARAM_ITEMS_PER_PAGE => static::REQUEST_PARAM_ITEMS_PER_PAGE_VALUE,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param int $idConfigurableBundleTemplateSlot
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    protected function findSelectedProductConcreteForSlot(FormInterface $form, int $idConfigurableBundleTemplateSlot): ?ProductConcreteTransfer
    {
        $sku = $form->getData()[ConfiguratorStateForm::FILED_SLOTS][$idConfigurableBundleTemplateSlot][SlotStateForm::FILED_SKU] ?? null;

        if (!$sku) {
            return null;
        }

        $productConcreteTransfers = $this->getFactory()
            ->getConfigurableBundleStorageClient()
            ->getProductConcreteStoragesBySkusForCurrentLocale([$sku]);

        return $productConcreteTransfers[$sku] ?? null;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return string[]
     */
    protected function extractProductConcreteSkus(FormInterface $form): array
    {
        $skus = [];

        foreach ($form->getData()[ConfiguratorStateForm::FILED_SLOTS] as $slotStateFormData) {
            $skus[] = $slotStateFormData[SlotStateForm::FILED_SKU];
        }

        return $skus;
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
