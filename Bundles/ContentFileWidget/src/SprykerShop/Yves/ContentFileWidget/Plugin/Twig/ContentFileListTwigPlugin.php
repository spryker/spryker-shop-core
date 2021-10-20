<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentFileWidget\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFilter;

/**
 * @method \SprykerShop\Yves\ContentFileWidget\ContentFileWidgetFactory getFactory()
 */
class ContentFileListTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    /**
     * @var string
     */
    protected const FILTER_NAME = 'readable_bytesize';

    /**
     * @var array
     */
    protected const LABEL_SIZES = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

    /**
     * @var int
     */
    protected const NUMBER_OF_DECIMALS = 1;

    /**
     * {@inheritDoc}
     * - The plugin displays a content file list.
     *
     * @api
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $contentFileListTwigFunction = $this->getFactory()
            ->createContentFileListTwigFunction($twig, $this->getLocale());

        $twig->addFunction($contentFileListTwigFunction);
        $twig->addFilter($this->createFilter());

        return $twig;
    }

    /**
     * @return \Twig\TwigFilter
     */
    protected function createFilter(): TwigFilter
    {
        return new TwigFilter(static::FILTER_NAME, function ($fileSize): string {
            $power = floor(log($fileSize, 1024));
            $calculatedSize = number_format($fileSize / (1024 ** $power), static::NUMBER_OF_DECIMALS);

            return sprintf('%s %s', $calculatedSize, static::LABEL_SIZES[$power]);
        });
    }
}
