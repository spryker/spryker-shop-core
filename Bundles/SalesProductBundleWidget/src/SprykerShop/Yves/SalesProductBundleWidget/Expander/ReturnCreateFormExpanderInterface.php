<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesProductBundleWidget\Expander;

interface ReturnCreateFormExpanderInterface
{
    /**
     * @param array $formData
     *
     * @return array
     */
    public function expandFormData(array $formData): array;
}
