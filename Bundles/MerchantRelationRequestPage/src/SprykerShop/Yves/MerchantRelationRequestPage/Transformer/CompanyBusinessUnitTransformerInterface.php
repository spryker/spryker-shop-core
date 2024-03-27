<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Transformer;

use Symfony\Component\Form\CallbackTransformer;

interface CompanyBusinessUnitTransformerInterface
{
    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    public function transformCollection(): CallbackTransformer;
}
