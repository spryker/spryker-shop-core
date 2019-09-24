<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyUserInvitationPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class CompanyUserInvitationPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_OVERVIEW = 'company/user-invitation';
    protected const ROUTE_GET_IMPORT_ERRORS = 'company/user-invitation/get-import-errors';

    protected const ROUTE_INVITATION_SEND = 'company/user-invitation/send';
    protected const ROUTE_INVITATION_SEND_ALL = 'company/user-invitation/send-all';

    protected const ROUTE_INVITATION_RESEND = 'company/user-invitation/resend';
    protected const ROUTE_INVITATION_RESEND_CONFIRM = 'company/user-invitation/resend/confirm';

    /**
     * @see \Spryker\Shared\CompanyUserInvitation\CompanyUserInvitationConstants::ROUTE_INVITATION_ACCEPT
     */
    protected const ROUTE_INVITATION_ACCEPT = 'invitation/accept';

    protected const ROUTE_INVITATION_DELETE = 'company/user-invitation/delete';
    protected const ROUTE_INVITATION_DELETE_CONFIRM = 'company/user-invitation/delete/confirm';

    /**
     * Specification:
     * - Adds Routes to the RouteCollection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addUserInvitationRoute($routeCollection);
        $routeCollection = $this->addUserInvitationErrorsRoute($routeCollection);
        $routeCollection = $this->addUserInvitationSendRoute($routeCollection);
        $routeCollection = $this->addUserInvitationSendAllRoute($routeCollection);
        $routeCollection = $this->addUserInvitationResendRoute($routeCollection);
        $routeCollection = $this->addUserInvitationResendConfirmRoute($routeCollection);
        $routeCollection = $this->addUserInvitationDeleteRoute($routeCollection);
        $routeCollection = $this->addUserInvitationDeleteConfirmRoute($routeCollection);
        $routeCollection = $this->addUserInvitationAcceptRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addUserInvitationRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/company/user-invitation', 'CompanyUserInvitationPage', 'Import', 'indexAction');
        $routeCollection->add(static::ROUTE_OVERVIEW, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addUserInvitationErrorsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/company/user-invitation/get-import-errors', 'CompanyUserInvitationPage', 'Import', 'getErrorsAction');
        $routeCollection->add(static::ROUTE_GET_IMPORT_ERRORS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addUserInvitationSendRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/company/user-invitation/send', 'CompanyUserInvitationPage', 'Send', 'sendCompanyUserInvitationAction');
        $routeCollection->add(static::ROUTE_INVITATION_SEND, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addUserInvitationSendAllRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/company/user-invitation/send-all', 'CompanyUserInvitationPage', 'Send', 'sendCompanyUserInvitationsAction');
        $routeCollection->add(static::ROUTE_INVITATION_SEND_ALL, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addUserInvitationResendRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/company/user-invitation/resend', 'CompanyUserInvitationPage', 'Resend', 'indexAction');
        $routeCollection->add(static::ROUTE_INVITATION_RESEND, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addUserInvitationResendConfirmRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/company/user-invitation/resend/confirm', 'CompanyUserInvitationPage', 'Resend', 'confirmAction');
        $routeCollection->add(static::ROUTE_INVITATION_RESEND_CONFIRM, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addUserInvitationDeleteRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/company/user-invitation/delete', 'CompanyUserInvitationPage', 'Delete', 'indexAction');
        $routeCollection->add(static::ROUTE_INVITATION_DELETE, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addUserInvitationDeleteConfirmRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/company/user-invitation/delete/confirm', 'CompanyUserInvitationPage', 'Delete', 'confirmAction');
        $routeCollection->add(static::ROUTE_INVITATION_DELETE_CONFIRM, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addUserInvitationAcceptRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/invitation/accept', 'CompanyUserInvitationPage', 'Accept', 'indexAction');
        $routeCollection->add(static::ROUTE_INVITATION_ACCEPT, $route);

        return $routeCollection;
    }
}
