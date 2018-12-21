<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderCompactWidget\Widget;

use Spryker\Yves\Kernel\Dependency\Widget\WidgetInterface;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\QuickOrderCompactWidget\ProductSearchWidgetConfig getConfig()
 */
class QuickOrderCompactWidget extends AbstractWidget implements WidgetInterface
{
    protected const NAME = 'QuickOrderCompactWidget';

    /**
     *
     * @param string $title
     * @param string $submitButtonTitle
     * @param string $submitUrl
     */
    public function __construct(string $title, string $submitButtonTitle, string $submitUrl)
    {
        $quickOrderCompactForm = $this->getQuickOrderCompactForm();

        $this->addParameter('title', $title)
            ->addParameter('submitButtonTitle', $submitButtonTitle)
            ->addParameter('submitUrl', $submitUrl)
            ->addParameter('form', $quickOrderCompactForm->createView());
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@QuickOrderCompactWidget/views/quick-order-form/quick-order-form.twig';
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getQuickOrderCompactForm(): FormInterface
    {
        return $this->getFactory()->getQuickOrderCompactForm();
    }
}
