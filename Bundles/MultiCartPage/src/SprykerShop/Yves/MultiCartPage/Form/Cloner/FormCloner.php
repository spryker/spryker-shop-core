<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage\Form\Cloner;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\FormInterface;

class FormCloner extends AbstractType
{
    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    protected $form;

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     */
    public function __construct(FormInterface $form)
    {
        $this->form = $form;
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getForm()
    {
        return clone $this->form;
    }
}
