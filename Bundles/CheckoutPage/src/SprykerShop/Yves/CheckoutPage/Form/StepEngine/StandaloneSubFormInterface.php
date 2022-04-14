<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Form\StepEngine;

use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;

interface StandaloneSubFormInterface extends SubFormInterface
{
    /**
     * @return string
     */
    public function getLabelName(): string;

    /**
     * @return string
     */
    public function getGroupName(): string;
}
