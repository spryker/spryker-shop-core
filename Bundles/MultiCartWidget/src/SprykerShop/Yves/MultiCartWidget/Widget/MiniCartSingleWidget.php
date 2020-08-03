<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\Form\FormView;

/**
 * @method \SprykerShop\Yves\MultiCartWidget\MultiCartWidgetFactory getFactory()
 */
class MiniCartSingleWidget extends AbstractWidget
{
    protected const PARAMETER_FORM = 'form';
    protected const OPTION_ID_QUOTE = 'idQuote';

    /**
     * @param int $idQuote
     */
    public function __construct(int $idQuote)
    {
        $this->addFormParameter();
    }

    /**
     * @return void
     */
    protected function addFormParameter(): void
    {
        $this->addParameter(static::PARAMETER_FORM, $this->addMiniCartRadioForm($idQuote));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'MiniCartSingleWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MultiCartWidget/views/mini-cart-single/mini-cart-single.twig';
    }

    /**
     * @param int $idQuote
     *
     * @return \Symfony\Component\Form\FormView
     */
    protected function addMiniCartRadioForm(int $idQuote): FormView
    {
        $options = [
            static::OPTION_ID_QUOTE => $idQuote,
        ];

        return $this->getFactory()
            ->getMultiCartRadioForm([], $options)
            ->createView();
    }
}
