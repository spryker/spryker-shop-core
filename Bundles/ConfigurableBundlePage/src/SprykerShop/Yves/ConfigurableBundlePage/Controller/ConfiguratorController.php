<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Controller;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchRequestTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Generated\Shared\Transfer\ConfiguratorStateTransfer;
use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
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
    use PermissionAwareTrait;

    /**
     * @uses \SprykerShop\Yves\ConfigurableBundlePage\Plugin\Router\ConfigurableBundlePageRouteProviderPlugin::ROUTE_CONFIGURATOR_TEMPLATE_SELECTION
     *
     * @var string
     */
    protected const ROUTE_CONFIGURATOR_TEMPLATE_SELECTION = 'configurable-bundle/configurator/template-selection';

    /**
     * @uses \SprykerShop\Yves\ConfigurableBundlePage\Plugin\Router\ConfigurableBundlePageRouteProviderPlugin::ROUTE_CONFIGURATOR_SLOTS
     *
     * @var string
     */
    protected const ROUTE_CONFIGURATOR_SLOTS = 'configurable-bundle/configurator/slots';

    /**
     * @uses \SprykerShop\Yves\ConfigurableBundlePage\Plugin\Router\ConfigurableBundlePageRouteProviderPlugin::ROUTE_CONFIGURATOR_SUMMARY
     *
     * @var string
     */
    protected const ROUTE_CONFIGURATOR_SUMMARY = 'configurable-bundle/configurator/summary';

    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin::ROUTE_NAME_CART
     *
     * @var string
     */
    protected const ROUTE_CART = 'cart';

    /**
     * @uses \SprykerShop\Yves\ConfigurableBundlePage\Plugin\Router\ConfigurableBundlePageRouteProviderPlugin::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE
     *
     * @var string
     */
    protected const PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE = 'idConfigurableBundleTemplate';

    /**
     * @uses \SprykerShop\Yves\ConfigurableBundlePage\Plugin\Router\ConfigurableBundlePageRouteProviderPlugin::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT
     *
     * @var string
     */
    protected const PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT = 'idConfigurableBundleTemplateSlot';

    /**
     * @uses \Spryker\Client\ConfigurableBundlePageSearch\Plugin\Elasticsearch\ResultFormatter\ConfigurableBundleTemplatePageSearchResultFormatterPlugin::NAME
     *
     * @var string
     */
    protected const FORMATTED_RESULT_KEY = 'ConfigurableBundleTemplateCollection';

    /**
     * @uses \Spryker\Client\ProductListSearch\Plugin\Search\ProductListQueryExpanderPlugin::REQUEST_PARAM_ID_PRODUCT_LIST
     */
    protected const REQUEST_PARAM_ID_PRODUCT_LIST = ProductListTransfer::ID_PRODUCT_LIST;

    /**
     * @uses \Spryker\Client\Catalog\CatalogConfig::PAGINATION_ITEMS_PER_PAGE_PARAMETER_NAME
     *
     * @var string
     */
    protected const REQUEST_PARAM_ITEMS_PER_PAGE = 'ipp';

    /**
     * @see \SprykerShop\Yves\CatalogPage\CatalogPageConfig::CATALOG_PAGE_LIMIT
     *
     * @var int
     */
    protected const REQUEST_PARAM_ITEMS_PER_PAGE_VALUE = 1000;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CONFIGURATOR_SUMMARY_PAGE_LOCKED = 'configurable_bundle_page.configurator.summary_page_locked';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND = 'configurable_bundle_page.template_not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_INVALID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_COMBINATION = 'configurable_bundle_page.invalid_template_slot_combination';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CONFIGURED_BUNDLE_ADDED_TO_CART = 'configurable_bundle_page.configurator.added_to_cart';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PERMISSION_FAILED = 'global.permission.failed';

    /**
     * @var string
     */
    protected const MESSAGE_FORM_CSRF_VALIDATION_ERROR = 'form.csrf.error.text';

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
                new ConfigurableBundleTemplatePageSearchRequestTransfer(),
            );

        return [
            'configurableBundleTemplates' => $formattedSearchResults[static::FORMATTED_RESULT_KEY] ?? [],
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function executeSlotsAction(Request $request)
    {
        $idConfigurableBundleTemplate = $request->attributes->getInt(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE);
        $idConfigurableBundleTemplateSlot = $request->attributes->getInt(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT);

        $configurableBundleTemplateStorageTransfer = $this->getFactory()
            ->createConfigurableBundleTemplateStorageReader()
            ->findConfigurableBundleTemplateStorage($idConfigurableBundleTemplate, $this->getLocale());

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

        $selectedProductViewTransfer = $this->findSelectedProductConcreteForSlot($form, $idConfigurableBundleTemplateSlot);
        /** @var array<int> $excludedProductIds */
        $excludedProductIds = $selectedProductViewTransfer ? [$selectedProductViewTransfer->getIdProductConcrete()] : [];

        $productConcreteCriteriaFilterTransfer = $this->createProductConcreteCriteriaFilterTransfer(
            $configurableBundleTemplateStorageTransfer,
            $idConfigurableBundleTemplateSlot,
            $excludedProductIds,
        );

        $response = array_merge($response, [
            'selectedSlotId' => $idConfigurableBundleTemplateSlot,
            'selectedProduct' => $selectedProductViewTransfer,
            'productConcreteCriteriaFilter' => $productConcreteCriteriaFilterTransfer,
            'isSummaryPageUnlocked' => $this->isSummaryPageUnlocked($form),
        ]);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function executeSummaryAction(Request $request)
    {
        $idConfigurableBundleTemplate = $request->attributes->getInt(static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE);

        $configurableBundleTemplateStorageTransfer = $this->getFactory()
            ->createConfigurableBundleTemplateStorageReader()
            ->findConfigurableBundleTemplateStorage($idConfigurableBundleTemplate, $this->getLocale());

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

        $configuratorStateTransfer = (new ConfiguratorStateTransfer())
            ->setSlotStateFormsData($form->getData()[ConfiguratorStateForm::FIELD_SLOTS])
            ->setConfigurableBundleTemplateStorage($configurableBundleTemplateStorageTransfer)
            ->setProducts(new ArrayObject($productViewTransfers));

        $configuratorStateTransfer = $this->getFactory()
            ->createConfiguratorStateSanitizer()
            ->sanitizeConfiguratorStateFormData($configuratorStateTransfer);

        if ($configuratorStateTransfer->getIsStateModified()) {
            foreach ($configuratorStateTransfer->getMessages() as $messageTransfer) {
                $this->addErrorMessage(
                    $this->getFactory()
                        ->getGlossaryStorageClient()
                        ->translate($messageTransfer->getValue(), $this->getLocale(), $messageTransfer->getParameters()),
                );
            }

            $parameters = $request->query->all();
            $parameters[$form->getName()][ConfiguratorStateForm::FIELD_SLOTS] = $configuratorStateTransfer->getSlotStateFormsData();
            $parameters[static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE] = $idConfigurableBundleTemplate;

            return $this->redirectResponseInternal(static::ROUTE_CONFIGURATOR_SUMMARY, $parameters);
        }

        return [
            'form' => $form,
            'configurableBundleTemplateStorage' => $configurableBundleTemplateStorageTransfer,
            'products' => $configuratorStateTransfer->getProducts()->getArrayCopy(),
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

        if (!$this->can('AddCartItemPermissionPlugin')) {
            $this->addErrorMessage(static::GLOSSARY_KEY_PERMISSION_FAILED);

            $parameters = $request->request->all();
            $parameters[static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE] = $idConfigurableBundleTemplate;

            return $this->redirectResponseInternal(static::ROUTE_CONFIGURATOR_SUMMARY, $parameters);
        }

        $form = $this->getFactory()->getConfiguratorStateForm();

        $data = $request->request->all($form->getName());
        $formData = $data[ConfiguratorStateForm::FIELD_SLOTS] ?? null;

        $form->submit($data);
        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_VALIDATION_ERROR);

            return $this->redirectResponseInternal(static::ROUTE_CONFIGURATOR_TEMPLATE_SELECTION);
        }

        if (!$formData) {
            return $this->redirectResponseInternal(static::ROUTE_CONFIGURATOR_TEMPLATE_SELECTION);
        }

        $configurableBundleTemplateStorageTransfer = $this->getFactory()
            ->createConfigurableBundleTemplateStorageReader()
            ->findConfigurableBundleTemplateStorage($idConfigurableBundleTemplate, $this->getLocale());

        if (!$configurableBundleTemplateStorageTransfer) {
            $this->addErrorMessage(static::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND);

            return $this->redirectResponseInternal(static::ROUTE_CONFIGURATOR_TEMPLATE_SELECTION);
        }

        $createConfiguredBundleRequestTransfer = (new CreateConfiguredBundleRequestTransfer())
            ->setLocaleName($this->getLocale());

        $createConfiguredBundleRequestTransfer = $this->getFactory()
            ->createConfiguredBundleRequestMapper()
            ->mapDataToCreateConfiguredBundleRequestTransfer(
                $formData,
                $configurableBundleTemplateStorageTransfer,
                $createConfiguredBundleRequestTransfer,
            );

        $quoteResponseTransfer = $this->getFactory()
            ->getConfigurableBundleCartClient()
            ->addConfiguredBundle($createConfiguredBundleRequestTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            foreach ($quoteResponseTransfer->getErrors() as $quoteErrorTransfer) {
                $this->addErrorMessage($quoteErrorTransfer->getMessage());
            }

            $parameters = $request->request->all();
            $parameters[static::PARAM_ID_CONFIGURABLE_BUNDLE_TEMPLATE] = $idConfigurableBundleTemplate;

            return $this->redirectResponseInternal(static::ROUTE_CONFIGURATOR_SUMMARY, $parameters);
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
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     * @param int $idConfigurableBundleTemplateSlot
     * @param array<int> $excludedProductIds
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCriteriaFilterTransfer
     */
    protected function createProductConcreteCriteriaFilterTransfer(
        ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer,
        int $idConfigurableBundleTemplateSlot,
        array $excludedProductIds
    ): ProductConcreteCriteriaFilterTransfer {
        /** @var \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer $configurableBundleTemplateSlotStorageTransfer */
        $configurableBundleTemplateSlotStorageTransfer = $configurableBundleTemplateStorageTransfer->getSlots()->offsetGet($idConfigurableBundleTemplateSlot);

        return (new ProductConcreteCriteriaFilterTransfer())
            ->setExcludedProductIds($excludedProductIds)
            ->setRequestParams([
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
     * @return array<string>
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
