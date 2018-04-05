<?php

namespace SprykerShop\Yves\ShoppingListPage\Form\Constraint;

use Symfony\Component\Validator\Constraint;

class ShareShoppingListRequiredIdConstraint extends Constraint
{
    /**
     * @return string
     */
    public function getTargets()
    {
        return static::CLASS_CONSTRAINT;
    }
}
