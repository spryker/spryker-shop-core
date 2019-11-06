<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Twig;

use Spryker\Shared\Twig\TwigFunction;
use Spryker\Yves\CmsContentWidget\Plugin\CmsTwigContentRendererPluginInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class CmsBlockPlaceholderTwigFunction extends TwigFunction
{
    protected const SPY_CMS_BLOCK_PLACEHOLDER_TWIG_FUNCTION = 'spyCmsBlockPlaceholder';
    protected const CMS_BLOCK_PREFIX_KEY = 'generated.cms.cms-block';

    /**
     * @var \Symfony\Contracts\Translation\TranslatorInterface
     */
    protected $translator;

    /**
     * @var \Spryker\Yves\CmsContentWidget\Plugin\CmsTwigContentRendererPluginInterface
     */
    protected $cmsTwigContentRendererPlugin;

    /**
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     * @param \Spryker\Yves\CmsContentWidget\Plugin\CmsTwigContentRendererPluginInterface $cmsTwigContentRendererPlugin
     */
    public function __construct(
        TranslatorInterface $translator,
        CmsTwigContentRendererPluginInterface $cmsTwigContentRendererPlugin
    ) {
        parent::__construct();
        $this->translator = $translator;
        $this->cmsTwigContentRendererPlugin = $cmsTwigContentRendererPlugin;
    }

    /**
     * @return string
     */
    protected function getFunctionName(): string
    {
        return static::SPY_CMS_BLOCK_PLACEHOLDER_TWIG_FUNCTION;
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            'needs_context' => true,
        ];
    }

    /**
     * @return callable
     */
    protected function getFunction(): callable
    {
        return function (array $context, $identifier) {
            $translation = $this->getTranslation($identifier, $context);

            return $this->renderCmsTwigContent($translation, $identifier, $context);
        };
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
            $translation = $this->translator->trans($translation);
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
        $renderedTwigContent = $this->cmsTwigContentRendererPlugin->render([$identifier => $translation], $context);

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
