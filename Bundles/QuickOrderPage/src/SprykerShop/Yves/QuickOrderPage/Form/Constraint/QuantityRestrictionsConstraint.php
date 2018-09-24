<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\Constraint;

use SprykerShop\Yves\QuickOrderPage\Model\QuickOrderProductQuantityRestrictionsValidatorInterface;
use Symfony\Component\Validator\Constraint;

class QuantityRestrictionsConstraint extends Constraint
{
    /**
     * @var \SprykerShop\Yves\QuickOrderPage\Model\QuickOrderProductQuantityRestrictionsValidatorInterface
     */
    protected $quickOrderProductQuantityRestrictionsValidator;

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\Model\QuickOrderProductQuantityRestrictionsValidatorInterface $quickOrderProductQuantityRestrictionsValidator
     * @param mixed|null $options
     */
    public function __construct(QuickOrderProductQuantityRestrictionsValidatorInterface $quickOrderProductQuantityRestrictionsValidator, $options = null)
    {
        parent::__construct($options);
        $this->quickOrderProductQuantityRestrictionsValidator = $quickOrderProductQuantityRestrictionsValidator;
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Model\QuickOrderProductQuantityRestrictionsValidatorInterface
     */
    public function getQuickOrderProductQuantityRestrictionsValidator(): QuickOrderProductQuantityRestrictionsValidatorInterface
    {
        return $this->quickOrderProductQuantityRestrictionsValidator;
    }
}
