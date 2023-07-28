<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointWidget\Hydrator;

use Symfony\Component\Form\FormEvent;

interface ServicePointFormPreSetDataHydratorInterface
{
    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    public function hydrate(FormEvent $event): void;
}
