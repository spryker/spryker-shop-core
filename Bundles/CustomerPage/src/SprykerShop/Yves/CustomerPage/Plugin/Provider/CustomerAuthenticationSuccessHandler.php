<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\Provider;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class CustomerAuthenticationSuccessHandler extends AbstractPlugin implements AuthenticationSuccessHandlerInterface
{
    /**
     * @see HomePageRouteProviderPlugin::ROUTE_HOME
     *
     * @var string
     */
    protected const ROUTE_HOME = 'home';

    /**
     * @var string
     */
    protected const PARAMETER_REQUIRES_ADDITIONAL_AUTH = 'requires_additional_auth';

    /**
     * @var string
     */
    protected const ACCESS_MODE_PRE_AUTH = 'ACCESS_MODE_PRE_AUTH';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        if (in_array(static::ACCESS_MODE_PRE_AUTH, $token->getRoleNames())) {
            return new JsonResponse([
                static::PARAMETER_REQUIRES_ADDITIONAL_AUTH => true,
            ]);
        }

        /** @var \SprykerShop\Yves\CustomerPage\Security\Customer $customer */
        $customer = $token->getUser();
        $this->executeOnAuthenticationSuccess($customer->getCustomerTransfer());

        return $this->createRedirectResponse($request);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function executeOnAuthenticationSuccess(CustomerTransfer $customerTransfer): void
    {
        $this->setCustomerSession($customerTransfer);

        $this->executeAfterPlugins();

        $this->getFactory()->createAuditLogger()->addSuccessfulLoginAuditLog();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function setCustomerSession(CustomerTransfer $customerTransfer)
    {
        $this->getFactory()
            ->getCustomerClient()
            ->setCustomer($customerTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function createRedirectResponse(Request $request)
    {
        $targetUrl = $this->determineTargetUrl($request);

        $response = $this->getFactory()->createRedirectResponse($targetUrl);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    protected function determineTargetUrl($request)
    {
        if ($request->headers->has('Referer')) {
            return (string)$request->headers->get('Referer');
        }

        return static::ROUTE_HOME;
    }

    /**
     * @return void
     */
    protected function executeAfterPlugins(): void
    {
        $afterCustomerAuthenticationSuccessPlugins = $this->getFactory()->getAfterCustomerAuthenticationSuccessPlugins();

        foreach ($afterCustomerAuthenticationSuccessPlugins as $afterCustomerAuthenticationSuccessPlugin) {
            $afterCustomerAuthenticationSuccessPlugin->execute();
        }
    }
}
