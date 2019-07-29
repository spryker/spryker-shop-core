<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Dependency\Service;

interface QuoteRequestPageToUtilDateTimeServiceInterface
{
    /**
     * @param \DateTime|string $date
     *
     * @return string
     */
    public function formatDate($date);
}
