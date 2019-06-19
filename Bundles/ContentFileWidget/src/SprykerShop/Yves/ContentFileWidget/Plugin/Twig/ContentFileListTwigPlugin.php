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

/**
 * @method \SprykerShop\Yves\ContentFileWidget\ContentFileWidgetFactory getFactory()
 */
class ContentFileListTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
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
        $twig->addFilter($this->getFactory()->createReadableByteSizeTwigFilter());

        return $twig;
    }
}
