<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShipmentTypeWidget\Hydrator;

use Symfony\Component\Form\FormEvent;

interface ShipmentTypeFormSubmitDataHydratorInterface
{
    /**
     * @param \Symfony\Component\Form\FormEvent $event
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function hydrate(FormEvent $event, array $options): void;
}
