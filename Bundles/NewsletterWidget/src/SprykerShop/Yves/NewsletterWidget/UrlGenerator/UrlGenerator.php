<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterWidget\UrlGenerator;

class UrlGenerator implements UrlGeneratorInterface
{
    /**
     * @param string $locale
     *
     * @return string
     */
    public function getMainPageUrlWithLocale(string $locale): string
    {
        $localeParts = explode('_', $locale);

        return '/' . $localeParts[0];
    }
}
