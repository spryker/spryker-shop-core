<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class QuickOrderItemsEventSubscriber implements EventSubscriberInterface
{
    /**
     * @uses \SprykerShop\Yves\QuickOrderPage\Form\QuickOrderForm::FIELD_ITEMS
     *
     * @var string
     */
    protected const FIELD_ITEMS = 'items';

    /**
     * @return array<string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'onPreSubmit',
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    public function onPreSubmit(FormEvent $event): void
    {
        $items = $event->getData();

        if (!$items[static::FIELD_ITEMS]) {
            return;
        }

        $items[static::FIELD_ITEMS] = $this->removeEmptyQuickOrderItems($items[static::FIELD_ITEMS]);

        $event->setData($items);
    }

    /**
     * @param array $quickOrderItems
     *
     * @return array
     */
    protected function removeEmptyQuickOrderItems(array $quickOrderItems): array
    {
        return array_filter(
            $quickOrderItems,
            function ($quickOrderItem) {
                foreach ($quickOrderItem as $value) {
                    if ($value) {
                        return true;
                    }
                }

                return false;
            },
        );
    }
}
