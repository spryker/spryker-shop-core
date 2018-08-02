<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class UserDeleteController extends AbstractController
{
    protected const PARAM_REQUEST_ID_COMPANY_USER = 'id';
    protected const PARAM_RESPONSE_ID_COMPANY_USER = 'idCompanyUser';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request): View
    {
        $idCompanyUser = $request->query->getInt(static::PARAM_REQUEST_ID_COMPANY_USER);

        return $this->view([
            static::PARAM_RESPONSE_ID_COMPANY_USER => $idCompanyUser,
        ], [], '@CompanyPage/views/user-delete/user-delete.twig');
    }
}
