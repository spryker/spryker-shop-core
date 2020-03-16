<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantPage\Controller;

use Generated\Shared\Transfer\MerchantStorageTransfer;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\MerchantPage\MerchantPageFactory getFactory()
 */
class MerchantController extends AbstractController
{
    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(MerchantStorageTransfer $merchantStorageTransfer, Request $request): View
    {
        return $this->view(
            [
                'merchant' => $merchantStorageTransfer,
            ],
            [],
            '@MerchantPage/views/merchant-profile/merchant-profile.twig'
        );
    }
}
