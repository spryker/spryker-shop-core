<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\MerchantRelationRequestWidget\MerchantRelationRequestWidgetFactory getFactory()
 */
class MerchantRelationRequestMenuItemWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_IS_VISIBLE = 'isVisible';

    /**
     * @var string
     */
    protected const PARAMETER_IS_ACTIVE_PAGE = 'isActivePage';

    /**
     * @var string
     */
    protected const PAGE_KEY_MERCHANT_RELATION_REQUEST = 'merchantRelationRequest';

    /**
     * @param string $activePage
     */
    public function __construct(string $activePage)
    {
        $this->addIsVisibleParameter();
        $this->addIsActivePageParameter($activePage);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'MerchantRelationRequestMenuItemWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MerchantRelationRequestWidget/views/merchant-relation-request-menu-item/merchant-relation-request-menu-item.twig';
    }

    /**
     * @return void
     */
    protected function addIsVisibleParameter(): void
    {
        $this->addParameter(static::PARAMETER_IS_VISIBLE, $this->isWidgetVisible());
    }

    /**
     * @param string $activePage
     *
     * @return void
     */
    protected function addIsActivePageParameter(string $activePage): void
    {
        $this->addParameter(static::PARAMETER_IS_ACTIVE_PAGE, $this->isMerchantRelationRequestPageActive($activePage));
    }

    /**
     * @param string $activePage
     *
     * @return bool
     */
    protected function isMerchantRelationRequestPageActive(string $activePage): bool
    {
        return $activePage === static::PAGE_KEY_MERCHANT_RELATION_REQUEST;
    }

    /**
     * @return bool
     */
    protected function isWidgetVisible(): bool
    {
        return (bool)$this->getFactory()->getCompanyUserClient()->findCompanyUser();
    }
}
