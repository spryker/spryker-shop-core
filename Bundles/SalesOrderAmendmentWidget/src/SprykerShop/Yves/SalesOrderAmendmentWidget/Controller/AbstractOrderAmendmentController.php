<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesOrderAmendmentWidget\Controller;

use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @method \SprykerShop\Yves\SalesOrderAmendmentWidget\SalesOrderAmendmentWidgetConfig getConfig()
 * @method \SprykerShop\Yves\SalesOrderAmendmentWidget\SalesOrderAmendmentWidgetFactory getFactory()
 */
abstract class AbstractOrderAmendmentController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToFailureUrl(): RedirectResponse
    {
        return $this->redirectResponseInternal(
            $this->getFactory()->getConfig()->getAmendmentFailureRedirectUrl(),
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToSuccessfulUrl(): RedirectResponse
    {
        return $this->redirectResponseInternal(
            $this->getFactory()->getConfig()->getAmendmentSuccessfulRedirectUrl(),
        );
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return void
     */
    protected function addErrorMessagesFromForm(FormInterface $form): void
    {
        /** @var list<\Symfony\Component\Form\FormError> $errors */
        $errors = $form->getErrors(true);
        foreach ($errors as $error) {
            $this->addErrorMessage($error->getMessage());
        }
    }
}
