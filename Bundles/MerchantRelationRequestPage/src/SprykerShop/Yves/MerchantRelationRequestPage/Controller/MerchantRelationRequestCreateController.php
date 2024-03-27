<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Controller;

use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\MerchantRelationRequestPage\Plugin\Router\MerchantRelationRequestPageRouteProviderPlugin;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\MerchantRelationRequestPage\MerchantRelationRequestPageFactory getFactory()
 */
class MerchantRelationRequestCreateController extends MerchantRelationRequestAbstractController
{
    use PermissionAwareTrait;

    /**
     * @uses \Spryker\Client\MerchantRelationRequest\Plugin\Permission\CreateMerchantRelationRequestPermissionPlugin::KEY
     *
     * @var string
     */
    protected const PERMISSION_KEY_CREATE_MERCHANT_RELATION_REQUEST = 'CreateMerchantRelationRequestPermissionPlugin';

    /**
     * @var string
     */
    protected const PARAMETER_MERCHANT_REFERENCE = 'merchant-reference';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_MERCHANT_RELATION_REQUEST_CREATED = 'merchant_relation_request_page.merchant_relation_request.created';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PERMISSION_FAILED = 'global.permission.failed';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request): View|RedirectResponse
    {
        $response = $this->executeCreateAction($request);
        if (!is_array($response)) {
            return $response;
        }

        return $this->view(
            $response,
            [],
            '@MerchantRelationRequestPage/views/merchant-relation-request-create/merchant-relation-request-create.twig',
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    protected function executeCreateAction(Request $request): RedirectResponse|array
    {
        if (!$this->can(static::PERMISSION_KEY_CREATE_MERCHANT_RELATION_REQUEST)) {
            $this->addErrorMessage(static::GLOSSARY_KEY_PERMISSION_FAILED);

            return $this->redirectResponseInternal(MerchantRelationRequestPageRouteProviderPlugin::ROUTE_NAME_MERCHANT_RELATION_REQUEST);
        }

        $merchantRelationRequestForm = $this->getFactory()
            ->getMerchantRelationRequestForm((string)$request->query->get(static::PARAMETER_MERCHANT_REFERENCE))
            ->handleRequest($request);

        if ($merchantRelationRequestForm->isSubmitted() && $merchantRelationRequestForm->isValid()) {
            return $this->processMerchantRelationRequestForm($merchantRelationRequestForm);
        }

        return ['merchantRelationRequestForm' => $merchantRelationRequestForm->createView()];
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $merchantRelationRequestForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    protected function processMerchantRelationRequestForm(FormInterface $merchantRelationRequestForm): RedirectResponse|array
    {
        $merchantRelationRequestResponseTransfer = $this->getFactory()
            ->createMerchantRelationRequestHandler()
            ->createMerchantRelationRequest($merchantRelationRequestForm->getData());

        if ($merchantRelationRequestResponseTransfer->getErrors()->count()) {
            $this->handleResponseErrors($merchantRelationRequestResponseTransfer);

            return ['merchantRelationRequestForm' => $merchantRelationRequestForm->createView()];
        }

        $this->addSuccessMessage(static::GLOSSARY_KEY_MERCHANT_RELATION_REQUEST_CREATED);
        $merchantRelationRequestUuid = $merchantRelationRequestResponseTransfer->getMerchantRelationRequests()
            ->getIterator()
            ->current()
            ->getUuidOrFail();

        return $this->redirectResponseInternal(
            MerchantRelationRequestPageRouteProviderPlugin::ROUTE_NAME_MERCHANT_RELATION_REQUEST_DETAILS,
            [static::PARAM_MERCHANT_RELATION_REQUEST_UUID => $merchantRelationRequestUuid],
        );
    }
}
