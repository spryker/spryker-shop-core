<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Plugin\Twig;

use ArrayObject;
use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;
use SprykerShop\Yves\ShopApplication\Plugin\AbstractTwigExtensionPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \SprykerShop\Yves\CmsBlockWidget\CmsBlockWidgetFactory getFactory()
 */
class CmsBlockWidgetTwigPlugin extends AbstractTwigExtensionPlugin
{
    /**
     * @var string
     */
    protected const FUNCTION_NAME_SPY_CMS_BLOCK = 'spyCmsBlock';

    /**
     * @var string
     */
    protected const STORAGE_DATA_KEY_CMS_BLOCK_GLOSSARY_KEY_MAPPINGS = 'SpyCmsBlockGlossaryKeyMappings';

    /**
     * @var string
     */
    protected const CMS_BLOCK_GLOSSARY_KEY_MAPPING_PLACEHOLDER = 'placeholder';

    /**
     * @var string
     */
    protected const CMS_BLOCK_GLOSSARY_KEY_MAPPING_GLOSSARY_KEY = 'GlossaryKey';

    /**
     * @var string
     */
    protected const CMS_BLOCK_GLOSSARY_KEY_MAPPING_KEY = 'key';

    /**
     * @return array<\Twig\TwigFunction>
     */
    public function getFunctions(): array
    {
        $locale = $this->getLocale();

        return [
            $this->createCmsBlockTwigFunction($locale),
        ];
    }

    /**
     * @param string $locale
     *
     * @return \Twig\TwigFunction
     */
    protected function createCmsBlockTwigFunction(string $locale): TwigFunction
    {
        return new TwigFunction(
            static::FUNCTION_NAME_SPY_CMS_BLOCK,
            function (Environment $twig, array $context, array $blockOptions = []) use ($locale): string {
                $storeName = $this->getFactory()
                    ->getStoreClient()
                    ->getCurrentStore()
                    ->getName();
                $cmsBlocks = $this->getFactory()
                    ->getCmsBlockStorageClient()
                    ->getCmsBlocksByOptions($blockOptions, $locale, $storeName);
                $rendered = '';

                foreach ($cmsBlocks as $cmsBlock) {
                    $rendered .= $this->renderCmsBlock($twig, $cmsBlock);
                }

                return $rendered;
            },
            [
                'needs_context' => true,
                'needs_environment' => true,
                'is_safe' => ['html'],
            ]
        );
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

        if (!$this->getFactory()->createCmsBlockValidator()->isValid($cmsBlockTransfer)) {
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
     * @return array<string>
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

        foreach ($cmsBlock[static::STORAGE_DATA_KEY_CMS_BLOCK_GLOSSARY_KEY_MAPPINGS] as $mapping) {
            $cmsBlockGlossaryPlaceholderTransfer = (new CmsBlockGlossaryPlaceholderTransfer())
                ->setPlaceholder($mapping[static::CMS_BLOCK_GLOSSARY_KEY_MAPPING_PLACEHOLDER])
                ->setTranslationKey($mapping[static::CMS_BLOCK_GLOSSARY_KEY_MAPPING_GLOSSARY_KEY][static::CMS_BLOCK_GLOSSARY_KEY_MAPPING_KEY]);

            $cmsBlockGlossaryPlaceholderTransfers->append($cmsBlockGlossaryPlaceholderTransfer);
        }

        $cmsBlockTransfer->setGlossary(
            (new CmsBlockGlossaryTransfer())
                ->setGlossaryPlaceholders($cmsBlockGlossaryPlaceholderTransfers)
        );

        return $cmsBlockTransfer;
    }
}
