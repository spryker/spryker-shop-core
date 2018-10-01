<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\Constraint;

use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToQuickOrderClientInterface;
use Symfony\Component\Validator\Constraint;

class QuantityRestrictionsConstraint extends Constraint
{
    /**
     * @var \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToQuickOrderClientInterface
     */
    protected $quickOrderClient;

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToQuickOrderClientInterface $quickOrderClient
     * @param mixed $options
     */
    public function __construct(QuickOrderPageToQuickOrderClientInterface $quickOrderClient, $options = null)
    {
        parent::__construct($options);
        $this->quickOrderClient = $quickOrderClient;
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToQuickOrderClientInterface
     */
    public function getQuickOrderClient(): QuickOrderPageToQuickOrderClientInterface
    {
        return $this->quickOrderClient;
    }
}
