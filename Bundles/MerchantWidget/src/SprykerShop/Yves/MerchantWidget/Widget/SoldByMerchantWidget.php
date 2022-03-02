<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantWidget\Widget;

use Generated\Shared\Transfer\MerchantStorageCriteriaTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\MerchantWidget\MerchantWidgetFactory getFactory()
 */
class SoldByMerchantWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_MERCHANT = 'merchant';

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $transfer
     */
    public function __construct(AbstractTransfer $transfer)
    {
        $this->addMerchantParameter($transfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'SoldByMerchantWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MerchantWidget/views/sold-by-merchant/sold-by-merchant.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $transfer
     *
     * @return $this
     */
    protected function addMerchantParameter(AbstractTransfer $transfer)
    {
        $this->addParameter(static::PARAMETER_MERCHANT, null);

        $merchantReference = $transfer->getMerchantReference();
        if ($merchantReference) {
            $merchantStorageTransfer = $this->getFactory()
                ->getMerchantStorageClient()
                ->findOne(
                    (new MerchantStorageCriteriaTransfer())
                        ->addMerchantReference($merchantReference),
                );
            $this->addParameter(static::PARAMETER_MERCHANT, $merchantStorageTransfer);
        }

        return $this;
    }
}
