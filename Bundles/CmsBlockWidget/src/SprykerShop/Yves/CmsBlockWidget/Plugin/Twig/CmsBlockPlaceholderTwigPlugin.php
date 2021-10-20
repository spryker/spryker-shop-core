<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Plugin\Twig;

use Spryker\Yves\Twig\Plugin\AbstractTwigExtensionPlugin;
use Twig\TwigFunction;

/**
 * @method \SprykerShop\Yves\CmsBlockWidget\CmsBlockWidgetFactory getFactory()
 */
class CmsBlockPlaceholderTwigPlugin extends AbstractTwigExtensionPlugin
{
    /**
     * @var string
     */
    protected const FUNCTION_NAME_SPY_CMS_BLOCK_PLACEHOLDER = 'spyCmsBlockPlaceholder';

    /**
     * @var string
     */
    protected const CMS_BLOCK_PREFIX_KEY = 'generated.cms.cms-block';

    /**
     * @return array<\Twig\TwigFunction>
     */
    public function getFunctions(): array
    {
        return [
            $this->createCmsBlockPlaceholderTwigFunction(),
        ];
    }

    /**
     * @return \Twig\TwigFunction
     */
    protected function createCmsBlockPlaceholderTwigFunction(): TwigFunction
    {
        return new TwigFunction(
            static::FUNCTION_NAME_SPY_CMS_BLOCK_PLACEHOLDER,
            function (array $context, $identifier) {
                $translation = $this->getTranslation($identifier, $context);

                return $this->renderCmsTwigContent($translation, $identifier, $context);
            },
            [
                'needs_context' => true,
            ],
        );
    }

    /**
     * @param string $identifier
     * @param array $context
     *
     * @return string
     */
    protected function getTranslation(string $identifier, array $context): string
    {
        $placeholders = $context['placeholders'];

        $translation = $placeholders[$identifier] ?? '';

        if ($this->isGlossaryKey($translation)) {
            $translation = $this->getFactory()
                ->getTranslatorService()
                ->trans($translation);
        }

        if ($this->isGlossaryKey($translation)) {
            $translation = '';
        }

        return $translation;
    }

    /**
     * @param string $translation
     * @param string $identifier
     * @param array $context
     *
     * @return string
     */
    protected function renderCmsTwigContent(string $translation, string $identifier, array $context): string
    {
        $renderedTwigContent = $this->getFactory()
            ->getCmsTwigContentRendererPlugin()
            ->render([$identifier => $translation], $context);

        return $renderedTwigContent[$identifier];
    }

    /**
     * @param string $translation
     *
     * @return bool
     */
    protected function isGlossaryKey(string $translation): bool
    {
        return strpos($translation, static::CMS_BLOCK_PREFIX_KEY) === 0;
    }
}
