<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNotesWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CartNotesWidget\Form\QuoteCartNoteForm;
use SprykerShop\Yves\CartNotesWidget\Form\QuoteItemCartNoteForm;
use SprykerShop\Yves\CartNotesWidget\Handler\CartNoteHandler;

class CartNotesWidgetFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCartNotesQuoteForm()
    {
        return $this->getFormFactory()->create(QuoteCartNoteForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCartNotesQuoteItemForm()
    {
        return $this->getFormFactory()->create(QuoteItemCartNoteForm::class);
    }

    /**
     * @return \SprykerShop\Yves\CartNotesWidget\Handler\CartNoteHandlerInterface
     */
    public function createCartNotesHandler()
    {
        return new CartNoteHandler($this->getCartClient());
    }

    /**
     * @return \SprykerShop\Yves\CartNotesWidget\Dependency\Client\CartNotesWidgetToCartClientInterface
     */
    protected function getCartClient()
    {
        return $this->getProvidedDependency(CartNotesWidgetDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    protected function getFormFactory()
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }
}
