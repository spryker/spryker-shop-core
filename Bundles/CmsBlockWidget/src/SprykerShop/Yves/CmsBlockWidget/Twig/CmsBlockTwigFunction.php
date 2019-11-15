<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Twig;

use ArrayObject;
use Generated\Shared\Transfer\SpyCmsBlockEntityTransfer;
use Spryker\Shared\Twig\TwigFunction;
use SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToCmsBlockStorageClientInterface;
use SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToStoreClientInterface;
use SprykerShop\Yves\CmsBlockWidget\Validator\CmsBlockValidatorInterface;
use Twig\Environment;

class CmsBlockTwigFunction extends TwigFunction
{
    protected const SPY_CMS_BLOCK_TWIG_FUNCTION = 'spyCmsBlock';

    /**
     * @var \SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToCmsBlockStorageClientInterface
     */
    protected $cmsBlockStorageClient;

    /**
     * @var \SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @var \SprykerShop\Yves\CmsBlockWidget\Validator\CmsBlockValidatorInterface
     */
    protected $cmsBlockValidator;

    /**
     * @var string
     */
    protected $localeName;

    /**
     * @param \SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToCmsBlockStorageClientInterface $cmsBlockStorageClient
     * @param \SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToStoreClientInterface $storeClient
     * @param \SprykerShop\Yves\CmsBlockWidget\Validator\CmsBlockValidatorInterface $cmsBlockValidator
     * @param string $localeName
     */
    public function __construct(
        CmsBlockWidgetToCmsBlockStorageClientInterface $cmsBlockStorageClient,
        CmsBlockWidgetToStoreClientInterface $storeClient,
        CmsBlockValidatorInterface $cmsBlockValidator,
        string $localeName
    ) {
        parent::__construct();
        $this->cmsBlockStorageClient = $cmsBlockStorageClient;
        $this->storeClient = $storeClient;
        $this->cmsBlockValidator = $cmsBlockValidator;
        $this->localeName = $localeName;
    }

    /**
     * @return string
     */
    protected function getFunctionName(): string
    {
        return static::SPY_CMS_BLOCK_TWIG_FUNCTION;
    }

    /**
     * @return callable
     */
    protected function getFunction(): callable
    {
        return function (Environment $twig, array $context, array $blockOptions = []): string {
            $storeName = $this->storeClient->getCurrentStore()->getName();
            $cmsBlocks = $this->cmsBlockStorageClient->getCmsBlocksByOptions($blockOptions, $this->localeName, $storeName);
            $rendered = '';

            foreach ($cmsBlocks as $cmsBlock) {
                $rendered .= $this->renderCmsBlock($twig, $cmsBlock);
            }

            return $rendered;
        };
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            'needs_context' => true,
            'needs_environment' => true,
            'is_safe' => ['html'],
        ];
    }

    /**
     * @param \Twig\Environment $twig
     * @param array $cmsBlock
     *
     * @return string
     */
    protected function renderCmsBlock(Environment $twig, array $cmsBlock): string
    {
        $spyCmsBlockTransfer = $this->getCmsBlockTransfer($cmsBlock);

        if (!$this->cmsBlockValidator->isValid($spyCmsBlockTransfer)) {
            return '';
        }

        $placeholders = $this->mapGlossaryKeysByPlaceholder($spyCmsBlockTransfer->getSpyCmsBlockGlossaryKeyMappings());

        return $twig->render($spyCmsBlockTransfer->getCmsBlockTemplate()->getTemplatePath(), [
            'placeholders' => $placeholders,
            'cmsContent' => $cmsBlock,
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyCmsBlockGlossaryKeyMappingEntityTransfer[]|\ArrayObject $cmsBlockGlossaryKeyMappings
     *
     * @return string[]
     */
    protected function mapGlossaryKeysByPlaceholder(ArrayObject $cmsBlockGlossaryKeyMappings): array
    {
        $placeholders = [];
        foreach ($cmsBlockGlossaryKeyMappings as $mapping) {
            $placeholders[$mapping->getPlaceholder()] = $mapping->getGlossaryKey()->getKey();
        }

        return $placeholders;
    }

    /**
     * @param array $cmsBlock
     *
     * @return \Generated\Shared\Transfer\SpyCmsBlockEntityTransfer
     */
    protected function getCmsBlockTransfer(array $cmsBlock): SpyCmsBlockEntityTransfer
    {
        return (new SpyCmsBlockEntityTransfer())->fromArray($cmsBlock, true);
    }
}
