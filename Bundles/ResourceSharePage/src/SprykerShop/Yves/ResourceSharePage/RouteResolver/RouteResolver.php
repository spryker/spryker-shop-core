<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ResourceSharePage\RouteResolver;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\RouteTransfer;
use SprykerShop\Yves\ResourceSharePage\Dependency\Client\ResourceSharePageToMessengerClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RouteResolver implements RouteResolverInterface
{
    /**
     * @uses \SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerPageControllerProvider::ROUTE_LOGIN
     */
    protected const ROUTE_LOGIN = 'login';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Plugin\CustomerPage\RedirectUriCustomerRedirectStrategyPlugin::PARAM_REDIRECT_URI
     */
    protected const PARAM_REDIRECT_URI = 'redirectUri';
    protected const GLOSSARY_KEY_RESOURCE_SHARE_LINK_ERROR_NO_ROUTE = 'resource-share.link.error.no-route';

    /**
     * @var \SprykerShop\Yves\ResourceSharePageExtension\Dependency\Plugin\ResourceShareRouterStrategyPluginInterface[]
     */
    protected $resourceShareRouterStrategyPlugins;

    /**
     * @var \SprykerShop\Yves\ResourceSharePage\Dependency\Client\ResourceSharePageToMessengerClientInterface
     */
    protected $messengerClient;

    /**
     * @param \SprykerShop\Yves\ResourceSharePage\Dependency\Client\ResourceSharePageToMessengerClientInterface $messengerClient
     * @param \SprykerShop\Yves\ResourceSharePageExtension\Dependency\Plugin\ResourceShareRouterStrategyPluginInterface[] $resourceShareRouterStrategyPlugins
     */
    public function __construct(ResourceSharePageToMessengerClientInterface $messengerClient, array $resourceShareRouterStrategyPlugins)
    {
        $this->messengerClient = $messengerClient;
        $this->resourceShareRouterStrategyPlugins = $resourceShareRouterStrategyPlugins;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param bool $isLoginRequired
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\RouteTransfer
     */
    public function resolveRoute(Request $request, bool $isLoginRequired, ResourceShareRequestTransfer $resourceShareRequestTransfer): RouteTransfer
    {
        if ($isLoginRequired) {
            return $this->getLoginRoute($request);
        }

        $resourceShareRequestTransfer->requireResourceShare();

        foreach ($this->resourceShareRouterStrategyPlugins as $resourceShareRouterStrategyPlugin) {
            if (!$resourceShareRouterStrategyPlugin->isApplicable($resourceShareRequestTransfer)) {
                continue;
            }

            return $resourceShareRouterStrategyPlugin->resolveRoute($resourceShareRequestTransfer);
        }

        $this->messengerClient->addErrorMessage(static::GLOSSARY_KEY_RESOURCE_SHARE_LINK_ERROR_NO_ROUTE);

        throw new NotFoundHttpException();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RouteTransfer
     */
    protected function getLoginRoute(Request $request): RouteTransfer
    {
        return (new RouteTransfer())
            ->setRoute(static::ROUTE_LOGIN)
            ->setParameters([
                static::PARAM_REDIRECT_URI => $request->getRequestUri(),
            ]);
    }
}
