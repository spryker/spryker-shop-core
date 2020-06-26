<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Form\Cloner;

use Spryker\Yves\Kernel\Form\AbstractType;
use SprykerShop\Yves\CustomerPage\Exception\FormNotFoundException;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageConfig getConfig()
 */
class FormCloner extends AbstractType
{
    /**
     * @var \Symfony\Component\Form\FormInterface|null
     */
    protected $form;

    /**
     * @throws \SprykerShop\Yves\CustomerPage\Exception\FormNotFoundException

     * @return \Symfony\Component\Form\FormInterface
     */
    public function getForm(): FormInterface
    {
        if ($this->form === null) {
            throw new FormNotFoundException('Form not found! You need to provide form for FormCloner class.');
        }

        return clone $this->form;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return $this
     */
    public function setForm(FormInterface $form)
    {
        $this->form = $form;

        return $this;
    }
}
