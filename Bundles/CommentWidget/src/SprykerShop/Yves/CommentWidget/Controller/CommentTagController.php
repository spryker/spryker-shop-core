<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CommentWidget\CommentWidgetFactory getFactory()
 */
class CommentTagController extends CommentWidgetAbstractController
{
    protected const PARAMETER_NAME = 'name';
    protected const PARAMETER_RETURN_URL = 'returnUrl';

    /**
     * @param string $uuid
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(string $uuid, Request $request): RedirectResponse
    {
        $response = $this->executeAddAction($uuid, $request);

        return $response;
    }

    /**
     * @param string $uuid
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(string $uuid, Request $request): RedirectResponse
    {
        $response = $this->executeRemoveAction($uuid, $request);

        return $response;
    }

    /**
     * @param string $uuid
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeAddAction(string $uuid, Request $request): RedirectResponse
    {
        return $this->redirectResponseExternal($request->request->get(static::PARAMETER_RETURN_URL));
    }

    /**
     * @param string $uuid
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeRemoveAction(string $uuid, Request $request): RedirectResponse
    {
        return $this->redirectResponseExternal($request->request->get(static::PARAMETER_RETURN_URL));
    }
}
