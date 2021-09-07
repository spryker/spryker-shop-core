<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class ProductReviewWidgetConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const GLOSSARY_KEY_INVALID_RATING_VALIDATION_MESSAGE = 'validation.choice';

    /**
     * @api
     *
     * @return string
     */
    public function getInvalidRatingValidationMessageGlossaryKey(): string
    {
        return static::GLOSSARY_KEY_INVALID_RATING_VALIDATION_MESSAGE;
    }
}
