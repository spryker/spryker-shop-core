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
    protected const KEY_BLOCK_KEY = 'blockKey';
    protected const KEY_CONDITIONS = 'conditions';
    protected const KEY_BLOCK_OPTIONS_KEYS = 'keys';

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
        $cmsBlockTwigFunction = $this->getCmsBlockTwigFunction();

        $content = $this->getContent($cmsBlockTwigFunction, $cmsSlotContentRequestTransfer);

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
        $blockOptions[static::KEY_BLOCK_OPTIONS_KEYS] = $this->getVisibleBlockKeys(
            $cmsSlotBlockStorageTransfer,
            $cmsSlotContentRequestTransfer
        );

        return $cmsBlockFunction($this->twig, [], $blockOptions);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer
     * @param \Generated\Shared\Transfer\CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
     *
     * @return array
     */
    protected function getVisibleBlockKeys(
        CmsSlotBlockStorageTransfer $cmsSlotBlockStorageTransfer,
        CmsSlotContentRequestTransfer $cmsSlotContentRequestTransfer
    ): array {
        $cmsBlocks = $cmsSlotBlockStorageTransfer->getCmsBlocks();
        $visibleBlockKeys = [];

        foreach ($cmsBlocks as $cmsBlock) {
            $cmsBlockTransfer = (new CmsBlockTransfer())->setKey($cmsBlock[static::KEY_BLOCK_KEY]);
            $isCmsBlockVisibleInSlot = $this->cmsSlotBlockClient->isCmsBlockVisibleInSlot(
                $cmsBlockTransfer,
                $cmsBlock[static::KEY_CONDITIONS],
                $cmsSlotContentRequestTransfer->getParams()
            );

            if ($isCmsBlockVisibleInSlot) {
                $visibleBlockKeys[] = $cmsBlock[static::KEY_BLOCK_KEY];
            }
        }

        return $visibleBlockKeys;
    }
}
