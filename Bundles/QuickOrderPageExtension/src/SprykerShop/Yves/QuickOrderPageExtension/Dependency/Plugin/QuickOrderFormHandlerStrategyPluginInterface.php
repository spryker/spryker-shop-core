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
     * - Checks if this QuickOrderForm handler strategy is applicable for the provided form and request data.
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
     * - Handles quick order form.
     * - Returns redirect response or null.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormInterface $quickOrderForm
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|null
     */
    public function execute(FormInterface $quickOrderForm, Request $request): ?RedirectResponse;
}
