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
    protected const PARAMETER_MERCHANT = 'merchant';

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $abstractTransfer
     */
    public function __construct(AbstractTransfer $abstractTransfer)
    {
        $this->addMerchantParameter($abstractTransfer);
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
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $abstractTransfer
     *
     * @return $this
     */
    protected function addMerchantParameter(AbstractTransfer $abstractTransfer)
    {
        $merchantStorageTransfer = null;
        if ($abstractTransfer->getMerchantReference()) {
            $merchantStorageTransfer = $this->getFactory()
                ->getMerchantStorageClient()
                ->findOne(
                    (new MerchantStorageCriteriaTransfer())
                        ->addMerchantReference($abstractTransfer->getMerchantReference())
                );
        }

        $this->addParameter(static::PARAMETER_MERCHANT, $merchantStorageTransfer);

        return $this;
    }
}
