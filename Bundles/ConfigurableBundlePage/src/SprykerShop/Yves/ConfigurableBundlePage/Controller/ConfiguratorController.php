<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Controller;

use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchRequestTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageRequestTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
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
     * @uses \SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin::ROUTE_CART
     */
    protected const ROUTE_CART = 'cart';

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
     * @uses \Spryker\Client\Catalog\CatalogConfig::PAGINATION_ITEMS_PER_PAGE_PARAMETER_NAME
     */
    protected const REQUEST_PARAM_ITEMS_PER_PAGE = 'ipp';

    /**
     * @see \SprykerShop\Yves\CatalogPage\CatalogPageConfig::CATALOG_PAGE_LIMIT
     */
    protected const REQUEST_PARAM_ITEMS_PER_PAGE_VALUE = 10000;

    protected const GLOSSARY_KEY_CONFIGURATOR_SUMMARY_PAGE_LOCKED = 'configurable_bundle_page.configurator.summary_page_locked';
    protected const GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND = 'configurable_bundle_page.template_not_found';
    protected const GLOSSARY_KEY_INVALID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_COMBINATION = 'configurable_bundle_page.invalid_template_slot_combination';
    protected const GLOSSARY_KEY_CONFIGURED_BUNDLE_ADDED_TO_CART = 'configurable_bundle_page.configurator.added_to_cart';

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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addToCartAction(Request $request): RedirectResponse
    {
        return $this->executeAddToCartAction($request);
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
            $this->addErrorMessage(static::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND);

            return $this->redirectResponseInternal(static::ROUTE_CONFIGURATOR_TEMPLATE_SELECTION);
        }

        if ($idConfigurableBundleTemplateSlot && !$configurableBundleTemplateStorageTransfer->getSlots()->offsetExists($idConfigurableBundleTemplateSlot)) {
            $this->addErrorMessage(static::GLOSSARY_KEY_INVALID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_COMBINATION);

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
            'selectedProduct' => $this->findSelectedProductConcreteForSlot($form, $idConfigurableBundleTemplateSlot),
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
            $this->addErrorMessage(static::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND);

            return $this->redirectResponseInternal(static::ROUTE_CONFIGURATOR_TEMPLATE_SELECTION);
        }

        $form = $this->getFactory()->getConfiguratorStateForm()->handleRequest($request);

        if (!$this->isSummaryPageUnlocked($form)) {
            $this->addErrorMessage(static::GLOSSARY_KEY_CONFIGURATOR_SUMMARY_PAGE_LOCKED);

            return $this->redirectResponseInternal(static::ROUTE_CONFIGURATOR_SLOTS, [
                static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE => $request->attributes->getInt(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE),
            ]);
        }

        $productViewTransfers = $this->getFactory()
            ->createProductConcreteReader()
            ->getProductConcretesBySkusAndLocale($this->extractProductConcreteSkus($form), $this->getLocale());

        return [
            'form' => $form,
            'configurableBundleTemplateStorage' => $configurableBundleTemplateStorageTransfer,
            'products' => $productViewTransfers,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function executeAddToCartAction(Request $request): RedirectResponse
    {
        $idConfigurableBundleTemplate = $request->attributes->getInt(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE);
        $form = $this->getFactory()->getConfiguratorStateForm();

        $formData = $request->request->get($form->getName())[ConfiguratorStateForm::FIELD_SLOTS] ?? null;

        if (!$formData) {
            return $this->redirectResponseInternal(static::ROUTE_CONFIGURATOR_TEMPLATE_SELECTION);
        }

        $configurableBundleTemplateStorageTransfer = $this->findConfigurableBundleTemplateStorage($idConfigurableBundleTemplate);

        if (!$configurableBundleTemplateStorageTransfer) {
            $this->addErrorMessage(static::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND);

            return $this->redirectResponseInternal(static::ROUTE_CONFIGURATOR_TEMPLATE_SELECTION);
        }

        $createConfiguredBundleRequestTransfer = $this->getFactory()
            ->createConfiguredBundleRequestMapper()
            ->mapDataToCreateConfiguredBundleRequestTransfer(
                $formData,
                $configurableBundleTemplateStorageTransfer,
                new CreateConfiguredBundleRequestTransfer()
            );

        $quoteResponseTransfer = $this->getFactory()
            ->getConfigurableBundleCartClient()
            ->addConfiguredBundle($createConfiguredBundleRequestTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            foreach ($quoteResponseTransfer->getErrors() as $quoteErrorTransfer) {
                $this->addErrorMessage($quoteErrorTransfer->getMessage());
            }

            return $this->redirectResponseInternal(static::ROUTE_CONFIGURATOR_SLOTS, [
                static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE => $request->attributes->getInt(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE),
            ]);
        }

        $this->addSuccessMessage(static::GLOSSARY_KEY_CONFIGURED_BUNDLE_ADDED_TO_CART);

        return $this->redirectResponseInternal(static::ROUTE_CART);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool
     */
    protected function isSummaryPageUnlocked(FormInterface $form): bool
    {
        $slotStateFormsData = $form->getData()[ConfiguratorStateForm::FIELD_SLOTS] ?? [];

        foreach ($slotStateFormsData as $slotStateFormData) {
            if (isset($slotStateFormData[SlotStateForm::FIELD_SKU])) {
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

        return $this->getFactory()
            ->createConfigurableBundleTemplateStorageReader()
            ->getConfigurableBundleTemplateStorage($configurableBundleTemplateStorageRequestTransfer);
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
     * @return \Generated\Shared\Transfer\ProductViewTransfer|null
     */
    protected function findSelectedProductConcreteForSlot(FormInterface $form, int $idConfigurableBundleTemplateSlot): ?ProductViewTransfer
    {
        $sku = $form->getData()[ConfiguratorStateForm::FIELD_SLOTS][$idConfigurableBundleTemplateSlot][SlotStateForm::FIELD_SKU] ?? null;

        if (!$sku) {
            return null;
        }

        $productViewTransfers = $this->getFactory()
            ->createProductConcreteReader()
            ->getProductConcretesBySkusAndLocale([$sku], $this->getLocale());

        return $productViewTransfers[$sku] ?? null;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return string[]
     */
    protected function extractProductConcreteSkus(FormInterface $form): array
    {
        $skus = [];

        foreach ($form->getData()[ConfiguratorStateForm::FIELD_SLOTS] as $slotStateFormData) {
            $skus[] = $slotStateFormData[SlotStateForm::FIELD_SKU];
        }

        return $skus;
    }
}
