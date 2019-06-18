<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentFileWidget\Twig;

use Spryker\Shared\Twig\TwigFilter;

class ReadableByteSizeTwigFilter extends TwigFilter
{
    protected const FILTER_READABLE_BITESIZE = 'readable_bytesize';
    protected const LABEL_SIZES = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    protected const NUMBER_OF_DECIMALS = 1;

    public function __construct()
    {
        parent::__construct($this->getFilterName(), $this->getFilter());
    }

    /**
     * @return string
     */
    public function getFilterName(): string
    {
        return static::FILTER_READABLE_BITESIZE;
    }

    /**
     * @return callable
     */
    public function getFilter(): callable
    {
        return function ($fileSize): string {
            $power = floor(log($fileSize, 1024));
            $calculatedSize = number_format($fileSize / (1024 ** $power), static::NUMBER_OF_DECIMALS);

            return sprintf('%s %s', $calculatedSize, static::LABEL_SIZES[$power]);
        };
    }
}
