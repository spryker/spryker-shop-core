<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\Widget;

use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\Form\FormView;

/**
 * @method \SprykerShop\Yves\MultiCartWidget\MultiCartWidgetFactory getFactory()
 */
class MiniCartSingleWidget extends AbstractWidget
{
    use PermissionAwareTrait;

    /**
     * @param int $idQuote
     */
    public function __construct(int $idQuote)
    {
        $this->addParameter('form', $this->addMiniCartRadioForm($idQuote));
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
        $data = [
            'idQuote' => $idQuote,
        ];

        return $this->getFactory()
            ->getMultiCartRadioForm($data, $data)
            ->createView();
    }
}
