<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetFactory getFactory()
 */
class CreateShoppingListFromCartWidget extends AbstractWidget
{
    protected const PARAMETER_IS_VISIBLE = 'isVisible';
    protected const PARAMETER_FORM = 'form';

    /**
     * @param int $idQuote
     */
    public function __construct(int $idQuote)
    {
        $this->addFormParameter($idQuote);
        $this->addIsVisibleParameter();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CreateShoppingListFromCartWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ShoppingListWidget/views/create-shopping-list-from-cart/create-shopping-list-from-cart.twig';
    }

    /**
     * @return void
     */
    protected function addIsVisibleParameter(): void
    {
        $this->addParameter(static::PARAMETER_IS_VISIBLE, $this->isVisible());
    }

    /**
     * @param int $idQuote
     *
     * @return void
     */
    protected function addFormParameter(int $idQuote): void
    {
        $form = null;
        if ($this->isVisible()) {
            $form = $this->getFactory()
                ->getShoppingListFromCartForm($idQuote)
                ->createView();
        }

        $this->addParameter(static::PARAMETER_FORM, $form);
    }

    /**
     * @return bool
     */
    protected function isVisible(): bool
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        return $customerTransfer && $customerTransfer->getCompanyUserTransfer();
    }
}
