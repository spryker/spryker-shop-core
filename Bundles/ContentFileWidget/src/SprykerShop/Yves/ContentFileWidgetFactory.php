<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentFileWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ContentFileWidget\Reader\ContentFileReader;
use SprykerShop\Yves\ContentFileWidget\Reader\ContentFileReaderInterface;
use SprykerShop\Yves\ContentFileWidget\Twig\ContentFileListTwigFunction;
use Twig\Environment;

class ContentFileWidgetFactory  extends AbstractFactory
{
    /**
     * @param Environment $twig
     * @param string $localeName
     *
     * @return \SprykerShop\Yves\ContentFileWidget\Twig\ContentFileListTwigFunction
     */
    public function createContentProductAbstractListTwigFunction(Environment $twig, string $localeName): ContentFileListTwigFunction
    {
        return new ContentFileListTwigFunction(
            $twig,
            $localeName,
            $this->createContentFileReader()
        );
    }

    /**
     * @return \SprykerShop\Yves\ContentFileWidget\Reader\ContentFileReaderInterface
     */
    public function createContentFileReader(): ContentFileReaderInterface
    {
        return new ContentFileReader();
    }
}
