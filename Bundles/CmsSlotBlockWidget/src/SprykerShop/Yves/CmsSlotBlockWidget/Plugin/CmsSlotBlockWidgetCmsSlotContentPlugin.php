<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsSlotBlockWidget\Plugin;

use Generated\Shared\Transfer\CmsSlotBlockStorageTransfer;
use Generated\Shared\Transfer\CmsSlotContentRequestTransfer;
use Generated\Shared\Transfer\CmsSlotContentResponseTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CmsSlotBlockWidget\Exceptions\CmsBlockTwigFunctionMissingException;
use SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotContentPluginInterface;
use Twig\Environment as TwigEnvironment;

/**
 * @method \SprykerShop\Yves\CmsSlotBlockWidget\CmsSlotBlockWidgetConfig getConfig()
 * @method \SprykerShop\Yves\CmsSlotBlockWidget\CmsSlotBlockWidgetFactory getFactory()
 */
class CmsSlotBlockWidgetCmsSlotContentPlugin extends AbstractPlugin implements CmsSlotContentPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotContentResponseTransfer
     */
    public function getSlotContent(
        CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
    ): CmsSlotContentResponseTransfer {
        $twig = $this->getFactory()->getTwigEnvironment();
        $cmsBlockFunction = $this->getCmsBlockTwigFunction($twig);

        $content = $this->getContent(
            $twig,
            $cmsBlockFunction,
            $cmsSlotContentRequestTransfer
        );

        return (new CmsSlotContentResponseTransfer())
            ->setContent($content);
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @throws \SprykerShop\Yves\CmsSlotBlockWidget\Exceptions\CmsBlockTwigFunctionMissingException
     *
     * @return callable
     */
    protected function getCmsBlockTwigFunction(TwigEnvironment $twig): callable
    {
        $twigFunction = $twig->getFunction($this->getConfig()->getCmsBlockTwigFunctionName());

        if (!$twigFunction) {
            throw new CmsBlockTwigFunctionMissingException(
                'You need to register \SprykerShop\Yves\CmsBlockWidget\Plugin\Twig\CmsBlockTwigPlugin in \Pyz\Yves\Twig\TwigDependencyProvider::getTwigPlugins().'
            );
        }

        return $twigFunction->getCallable();
    }

    /**
     * @param \Twig\Environment $twig
     * @param callable $cmsBlockFunction
     * @param \Generated\Shared\Transfer\CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
     *
     * @return string
     */
    protected function getContent(
        TwigEnvironment $twig,
        callable $cmsBlockFunction,
        CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
    ): string {
        $cmsSlotBlockStorageTransfer = $this->getFactory()
            ->getCmsSlotBlockStorageClient()
            ->getCmsSlotBlockCollection(
                $cmsSlotContentRequestTransfer->getCmsSlotTemplatePath(),
                $cmsSlotContentRequestTransfer->getCmsSlotKey()
            );
        //$blockOptions['keys'] = $this->getVisibleBlocksKeys($cmsSlotBlockStorageTransfer);
        $blockOptions['name'] = 'Category CMS page showcase for Top position';

        return $cmsBlockFunction($twig, [], $blockOptions);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer
     *
     * @return string[]
     */
    protected function getVisibleBlocksKeys(CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer): array
    {
        $cmsBlocks = $cmsSlotBlockStorageTransfer->getCmsBlocks();
        $visibleCmsBlockKeys = [];

        foreach ($cmsBlocks as $cmsBlock) {
            $visibleCmsBlockKeys[] = $cmsBlock['blockKey'];
        }

        return $visibleCmsBlockKeys;
    }
}
