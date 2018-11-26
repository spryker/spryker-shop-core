<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NewsletterWidget\UrlGenerator;

interface UrlGeneratorInterface
{
    /**
     * @param string $locale
     *
     * @return string
     */
    public function getMainPageUrlWithLocale(string $locale): string;
}
