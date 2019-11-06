<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsSlotBlockWidget\Business;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\CmsSlotBlockStorageTransfer;
use Generated\Shared\Transfer\CmsSlotContentRequestTransfer;
use Generated\Shared\Transfer\CmsSlotContentResponseTransfer;
use SprykerShop\Yves\CmsSlotBlockWidget\CmsSlotBlockWidgetConfig;
use SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client\CmsSlotBlockWidgetToCmsSlotBlockClientInterface;
use SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client\CmsSlotBlockWidgetToCmsSlotBlockStorageClientInterface;
use SprykerShop\Yves\CmsSlotBlockWidget\Exceptions\CmsBlockTwigFunctionMissingException;
use Twig\Environment;

class CmsSlotBlockWidgetDataProvider implements CmsSlotBlockWidgetDataProviderInterface
{
    /**
     * @var \Twig\Environment
     */
    protected $twig;

    /**
     * @var \SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client\CmsSlotBlockWidgetToCmsSlotBlockStorageClientInterface
     */
    protected $cmsSlotBlockStorageClient;

    /**
     * @var \SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client\CmsSlotBlockWidgetToCmsSlotBlockClientInterface
     */
    protected $cmsSlotBlockClient;

    /**
     * @var \SprykerShop\Yves\CmsSlotBlockWidget\CmsSlotBlockWidgetConfig
     */
    protected $cmsSlotBlockWidgetConfig;

    /**
     * @param \Twig\Environment $twig
     * @param \SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client\CmsSlotBlockWidgetToCmsSlotBlockStorageClientInterface $cmsSlotBlockStorageClient
     * @param \SprykerShop\Yves\CmsSlotBlockWidget\Dependency\Client\CmsSlotBlockWidgetToCmsSlotBlockClientInterface $cmsSlotBlockClient
     * @param \SprykerShop\Yves\CmsSlotBlockWidget\CmsSlotBlockWidgetConfig $cmsSlotBlockWidgetConfig
     */
    public function __construct(
        Environment $twig,
        CmsSlotBlockWidgetToCmsSlotBlockStorageClientInterface $cmsSlotBlockStorageClient,
        CmsSlotBlockWidgetToCmsSlotBlockClientInterface $cmsSlotBlockClient,
        CmsSlotBlockWidgetConfig $cmsSlotBlockWidgetConfig
    ) {
        $this->twig = $twig;
        $this->cmsSlotBlockStorageClient = $cmsSlotBlockStorageClient;
        $this->cmsSlotBlockClient = $cmsSlotBlockClient;
        $this->cmsSlotBlockWidgetConfig = $cmsSlotBlockWidgetConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotContentResponseTransfer
     */
    public function getSlotContent(
        CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
    ): CmsSlotContentResponseTransfer {
        $cmsBlockFunction = $this->getCmsBlockTwigFunction();

        $content = $this->getContent(
            $cmsBlockFunction,
            $cmsSlotContentRequestTransfer
        );

        return (new CmsSlotContentResponseTransfer())
            ->setContent($content);
    }

    /**
     * @throws \SprykerShop\Yves\CmsSlotBlockWidget\Exceptions\CmsBlockTwigFunctionMissingException
     *
     * @return callable
     */
    protected function getCmsBlockTwigFunction(): callable
    {
        $twigFunction = $this->twig->getFunction($this->cmsSlotBlockWidgetConfig->getCmsBlockTwigFunctionName());

        if (!$twigFunction) {
            throw new CmsBlockTwigFunctionMissingException(
                'You need to register \SprykerShop\Yves\CmsBlockWidget\Plugin\Twig\CmsBlockTwigPlugin in \Pyz\Yves\Twig\TwigDependencyProvider::getTwigPlugins().'
            );
        }

        return $twigFunction->getCallable();
    }

    /**
     * @param callable $cmsBlockFunction
     * @param \Generated\Shared\Transfer\CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
     *
     * @return string
     */
    protected function getContent(
        callable $cmsBlockFunction,
        CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
    ): string {
        $cmsSlotBlockStorageTransfer = $this->cmsSlotBlockStorageClient
            ->getCmsSlotBlockCollection(
                $cmsSlotContentRequestTransfer->getCmsSlotTemplatePath(),
                $cmsSlotContentRequestTransfer->getCmsSlotKey()
            );
        //$blockOptions['keys'] = $this->getVisibleBlockKeys($cmsSlotBlockStorageTransfer);
//
//        return $cmsBlockFunction($this->twig, [], $blockOptions);

        //TODO should be removed:
        $blockNames = $this->getVisibleBlockNames($cmsSlotBlockStorageTransfer, $cmsSlotContentRequestTransfer);
        $content = '';
        foreach ($blockNames as $blockName) {
            $blockOptions['name'] = $blockName;
            $content .= $cmsBlockFunction($this->twig, [], $blockOptions);
        }

        return $content;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer
     *
     * @return string[]
     */
    protected function getVisibleBlockKeys(CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer): array
    {
        $cmsBlocks = $cmsSlotBlockStorageTransfer->getCmsBlocks();
        $visibleCmsBlockKeys = [];

        foreach ($cmsBlocks as $cmsBlock) {
            $visibleCmsBlockKeys[] = $cmsBlock['blockKey'];
        }

        return $visibleCmsBlockKeys;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer
     * @param \Generated\Shared\Transfer\CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
     *
     * @return array
     */
    protected function getVisibleBlockNames(
        CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer,
        CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
    ): array {
        $cmsBlocks = $cmsSlotBlockStorageTransfer->getCmsBlocks();
        $visibleCmsBlockNames = [];

        foreach ($cmsBlocks as $cmsBlock) {
            if ($this->cmsSlotBlockClient->isCmsBlockVisibleInSlot(new CmsBlockTransfer(), $cmsBlock['conditions'], $cmsSlotContentRequestTransfer->getParams())) {
                $visibleCmsBlockNames[] = $cmsBlock['blockName'];
            }
        }

        return $visibleCmsBlockNames;
    }
}
