<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentBannerWidget\Twig;

use Spryker\Client\ContentBanner\Exception\MissingBannerTermException;
use Spryker\Shared\Twig\TwigFunction;
use SprykerShop\Yves\ContentBannerWidget\Dependency\Client\ContentBannerWidgetToContentBannerClientInterface;
use Twig\Environment;

class ContentBannerTwigFunction extends TwigFunction
{
    /**
     * @uses \Spryker\Shared\ContentBanner\ContentBannerConfig::TWIG_FUNCTION_NAME
     */
    protected const TWIG_FUNCTION_NAME_CONTENT_BANNER = 'content_banner';

    protected const MESSAGE_BANNER_NOT_FOUND = '<b>Content Banner with key %s not found.</b>';
    protected const MESSAGE_BANNER_WRONG_TYPE = '<b>Content Banner could not be rendered because the content item with key %s is not an banner.</b>';
    protected const MESSAGE_BANNER_WRONG_TEMPLATE = '<b>"%s" is not supported name of template.</b>';

    /**
     * @uses \Spryker\Shared\ContentBanner\ContentBannerConfig::WIDGET_TEMPLATE_IDENTIFIER_BOTTOM_TITLE
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_BOTTOM_TITLE = 'bottom-title';

    /**
     * @uses \Spryker\Shared\ContentBanner\ContentBannerConfig::WIDGET_TEMPLATE_IDENTIFIER_TOP_TITLE
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_TOP_TITLE = 'top-title';

    /**
     * @var \Twig\Environment
     */
    protected $twig;

    /**
     * @var string
     */
    protected $localeName;

    /**
     * @var \SprykerShop\Yves\ContentBannerWidget\Dependency\Client\ContentBannerWidgetToContentBannerClientInterface
     */
    protected $contentBannerClient;

    /**
     * @param \Twig\Environment $twig
     * @param string $localeName
     * @param \SprykerShop\Yves\ContentBannerWidget\Dependency\Client\ContentBannerWidgetToContentBannerClientInterface $contentBannerClient
     */
    public function __construct(
        Environment $twig,
        string $localeName,
        ContentBannerWidgetToContentBannerClientInterface $contentBannerClient
    ) {
        parent::__construct();
        $this->twig = $twig;
        $this->localeName = $localeName;
        $this->contentBannerClient = $contentBannerClient;
    }

    /**
     * @return string
     */
    protected function getFunctionName(): string
    {
        return static::TWIG_FUNCTION_NAME_CONTENT_BANNER;
    }

    /**
     * @return callable
     */
    protected function getFunction(): callable
    {
        return function (string $contentKey, string $templateIdentifier): ?string {
            if (!isset($this->getAvailableTemplates()[$templateIdentifier])) {
                return $this->getMessageBannerWrongTemplate($templateIdentifier);
            }
            try {
                $contentBannerTypeTransfer = $this->contentBannerClient->executeBannerTypeByKey($contentKey, $this->localeName);

                if (!$contentBannerTypeTransfer) {
                    return $this->getMessageBannerNotFound($contentKey);
                }
            } catch (MissingBannerTermException $e) {
                return $this->getMessageBannerWrongType($contentKey);
            }

            return $this->twig->render(
                $this->getAvailableTemplates()[$templateIdentifier],
                ['banner' => $contentBannerTypeTransfer]
            );
        };
    }

    /**
     * @return array
     */
    protected function getAvailableTemplates(): array
    {
        return [
            static::WIDGET_TEMPLATE_IDENTIFIER_BOTTOM_TITLE => '@ContentBannerWidget/views/banner/banner.twig',
            static::WIDGET_TEMPLATE_IDENTIFIER_TOP_TITLE => '@ContentBannerWidget/views/banner-alternative/banner-alternative.twig',
        ];
    }

    /**
     * @param string $contentKey
     *
     * @return string
     */
    protected function getMessageBannerNotFound(string $contentKey)
    {
        return sprintf(static::MESSAGE_BANNER_NOT_FOUND, $contentKey);
    }

    /**
     * @param string $templateIdentifier
     *
     * @return string
     */
    protected function getMessageBannerWrongTemplate(string $templateIdentifier)
    {
        return sprintf(static::MESSAGE_BANNER_WRONG_TEMPLATE, $templateIdentifier);
    }

    /**
     * @param string $contentKey
     *
     * @return string
     */
    protected function getMessageBannerWrongType(string $contentKey)
    {
        return sprintf(static::MESSAGE_BANNER_WRONG_TYPE, $contentKey);
    }
}
