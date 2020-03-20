<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyBusinessUnitWidget\Widget;

use ArrayObject;
use Generated\Shared\Transfer\FilterFieldCheckRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\CompanyBusinessUnitWidget\CompanyBusinessUnitWidgetFactory getFactory()
 */
class CustomerNameWidget extends AbstractWidget
{
    protected const PARAMETER_ORDER = 'order';
    protected const PARAMETER_IS_VISIBLE = 'isVisible';

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     * @param bool|null $forcedIsVisible
     */
    public function __construct(OrderTransfer $orderTransfer, array $filterFieldTransfers, ?bool $forcedIsVisible = null)
    {
        $this->addIsVisibleParameter($filterFieldTransfers, $forcedIsVisible);
        $this->addOrderParameter($orderTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CustomerNameWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CompanyBusinessUnitWidget/views/company-user-name/company-user-name.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function addOrderParameter(OrderTransfer $orderTransfer): void
    {
        $this->addParameter(static::PARAMETER_ORDER, $orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     * @param bool|null $forcedIsVisible
     *
     * @return void
     */
    protected function addIsVisibleParameter(array $filterFieldTransfers, ?bool $forcedIsVisible = null): void
    {
        if ($forcedIsVisible !== null) {
            $this->addParameter(static::PARAMETER_IS_VISIBLE, $forcedIsVisible);

            return;
        }

        $filterFieldCheckRequestTransfer = (new FilterFieldCheckRequestTransfer())->setFilterFields(
            new ArrayObject($filterFieldTransfers)
        );

        $isVisible = $this->getFactory()
            ->getCompanyBusinessUnitSalesConnectorClient()
            ->isCompanyRelatedFiltersSet($filterFieldCheckRequestTransfer)
            ->getIsSuccessful();

        $this->addParameter(static::PARAMETER_IS_VISIBLE, $isVisible);
    }
}
