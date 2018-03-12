<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNoteWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CartNoteWidget\Form\QuoteCartNoteForm;
use SprykerShop\Yves\CartNoteWidget\Form\QuoteItemCartNoteForm;
use SprykerShop\Yves\CartNoteWidget\Handler\CartNoteHandler;

class CartNoteWidgetFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCartNoteQuoteForm()
    {
        return $this->getFormFactory()->create(QuoteCartNoteForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCartNoteQuoteItemForm()
    {
        return $this->getFormFactory()->create(QuoteItemCartNoteForm::class);
    }

    /**
     * @return \SprykerShop\Yves\CartNoteWidget\Handler\CartNoteHandlerInterface
     */
    public function createCartNotesHandler()
    {
        return new CartNoteHandler($this->getCartClient());
    }

    /**
     * @return \SprykerShop\Yves\CartNoteWidget\Dependency\Client\CartNoteWidgetToCartClientInterface
     */
    protected function getCartClient()
    {
        return $this->getProvidedDependency(CartNoteWidgetDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    protected function getFormFactory()
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }
}
