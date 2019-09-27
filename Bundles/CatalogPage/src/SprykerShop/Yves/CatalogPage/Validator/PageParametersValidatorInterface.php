<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CatalogPage\Validator;

interface PageParametersValidatorInterface
{
    /**
     * @param array $parameters
     *
     * @return bool
     */
    public function validatePageParameters(array $parameters): bool;
}
