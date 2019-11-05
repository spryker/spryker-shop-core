<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProfilePage\Controller;

use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\MerchantProfilePage\MerchantProfilePageFactory getFactory()
 */
class MerchantProfileController extends AbstractController
{
    /**
     * @param array $merchantProfile
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(array $merchantProfile, Request $request): View
    {
        $merchantProfileStorageTransfer = $this->getFactory()
            ->getMerchantStorageClient()
            ->mapMerchantProfileStorageData($merchantProfile);

        $merchantOpeningHoursStorageTransfer = $this->getFactory()
            ->getMerchantOpeningHoursStoregeClient()
            ->findMerchantOpeningHoursByIdIdMerchant($merchantProfileStorageTransfer->getFkMerchant());

        return $this->view(
            [
                'merchantProfile' => $merchantProfileStorageTransfer,
                'merchantOpeningHours' => $merchantOpeningHoursStorageTransfer,
            ],
            [],
            '@MerchantProfilePage/views/merchant-profile/merchant-profile.twig'
        );
    }
}
