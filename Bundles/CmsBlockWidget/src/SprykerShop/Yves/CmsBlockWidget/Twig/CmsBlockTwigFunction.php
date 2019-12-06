<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Twig;

use ArrayObject;
use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Shared\Twig\TwigFunction;
use SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToCmsBlockStorageClientInterface;
use SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToStoreClientInterface;
use SprykerShop\Yves\CmsBlockWidget\Validator\CmsBlockValidatorInterface;
use Twig\Environment;

class CmsBlockTwigFunction extends TwigFunction
{
    protected const SPY_CMS_BLOCK_TWIG_FUNCTION = 'spyCmsBlock';
    protected const SPY_CMS_BLOCK_GLOSSARY_KEY_MAPPINGS = 'SpyCmsBlockGlossaryKeyMappings';
    protected const PLACEHOLDER = 'placeholder';
    protected const GLOSSARY_KEY = 'GlossaryKey';
    protected const KEY = 'key';

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
        $cmsBlockTransfer = $this->mapCmsBlockToTransfer($cmsBlock);

        if (!$this->cmsBlockValidator->isValid($cmsBlockTransfer)) {
            return '';
        }

        $placeholders = $this->getPlaceholders($cmsBlockTransfer);

        return $twig->render($cmsBlockTransfer->getCmsBlockTemplate()->getTemplatePath(), [
            'placeholders' => $placeholders,
            'cmsContent' => $cmsBlock,
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return string[]
     */
    protected function getPlaceholders(CmsBlockTransfer $cmsBlockTransfer): array
    {
        $placeholders = [];
        foreach ($cmsBlockTransfer->getGlossary()->getGlossaryPlaceholders() as $cmsBlockGlossaryPlaceholderTransfer) {
            $placeholders[$cmsBlockGlossaryPlaceholderTransfer->getPlaceholder()] = $cmsBlockGlossaryPlaceholderTransfer->getTranslationKey();
        }

        return $placeholders;
    }

    /**
     * @param array $cmsBlock
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    protected function mapCmsBlockToTransfer(array $cmsBlock): CmsBlockTransfer
    {
        $cmsBlockTransfer = (new CmsBlockTransfer())->fromArray($cmsBlock, true);

        $cmsBlockGlossaryPlaceholderTransfers = new ArrayObject();

        foreach ($cmsBlock[static::SPY_CMS_BLOCK_GLOSSARY_KEY_MAPPINGS] as $mapping) {
            $cmsBlockGlossaryPlaceholderTransfer = (new CmsBlockGlossaryPlaceholderTransfer())
                ->setPlaceholder($mapping[static::PLACEHOLDER])
                ->setTranslationKey($mapping[static::GLOSSARY_KEY][static::KEY]);

            $cmsBlockGlossaryPlaceholderTransfers->append($cmsBlockGlossaryPlaceholderTransfer);
        }

        $cmsBlockTransfer->setGlossary(
            (new CmsBlockGlossaryTransfer())
                ->setGlossaryPlaceholders($cmsBlockGlossaryPlaceholderTransfers)
        );

        return $cmsBlockTransfer;
    }
}
