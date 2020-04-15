<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Form\Listener;

use Symfony\Component\Form\FormEvent;

interface QuoteRequestAgentFormEventsListenerInterface
{
    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return \Symfony\Component\Form\FormEvent
     */
    public function copySubmittedShipmentMethodPricesToItemShipmentMethods(FormEvent $event): FormEvent;

    /**
     * @deprecated Will be removed without replacement. BC-reason only.
     *
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return \Symfony\Component\Form\FormEvent
     */
    public function copySubmittedItemShipmentMethodPricesToQuoteShipmentMethod(FormEvent $event): FormEvent;
}
