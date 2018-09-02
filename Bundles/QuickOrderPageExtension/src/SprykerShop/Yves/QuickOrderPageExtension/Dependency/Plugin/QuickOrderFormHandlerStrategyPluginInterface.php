<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

interface QuickOrderFormHandlerStrategyPluginInterface
{
    /**
     * Specification:
     *  - Checks if this plugin is applicable for provided form.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormInterface $quickOrderForm
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    public function isApplicable(FormInterface $quickOrderForm, Request $request): bool;

    /**
     * Specification:
     *  - Handle quick order form submit.
     *  - Returns redirect to response.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormInterface $quickOrderForm
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function execute(FormInterface $quickOrderForm, Request $request): ?RedirectResponse;
}
