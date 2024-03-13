<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopUi\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\MoneyType;

/**
 * @method \Spryker\Zed\Gui\Communication\GuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\Gui\GuiConfig getConfig()
 */
class FormattedMoneyType extends AbstractFormattedType
{
    /**
     * @var string
     */
    protected const BLOCK_PREFIX = 'formatted_money';

    /**
     * @return string
     */
    public function getParent(): string
    {
        return MoneyType::class;
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::BLOCK_PREFIX;
    }
}
