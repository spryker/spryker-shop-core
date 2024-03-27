<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationshipWidget\Widget;

use ArrayObject;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

class MerchantRelationshipLinkListWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_MERCHANT_RELATIONSHIPS = 'merchantRelationships';

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\MerchantRelationshipTransfer> $merchantRelationshipTransfers
     */
    public function __construct(ArrayObject $merchantRelationshipTransfers)
    {
        $this->addMerchantRelationshipsParameter($merchantRelationshipTransfers);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'MerchantRelationshipLinkListWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MerchantRelationshipWidget/views/merchant-relationship-link-list/merchant-relationship-link-list.twig';
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\MerchantRelationshipTransfer> $merchantRelationshipTransfers
     *
     * @return void
     */
    protected function addMerchantRelationshipsParameter(ArrayObject $merchantRelationshipTransfers): void
    {
        $this->addParameter(static::PARAMETER_MERCHANT_RELATIONSHIPS, $merchantRelationshipTransfers);
    }
}
