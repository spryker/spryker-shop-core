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
    protected const TWIG_FUNCTION_NAME_CONTNET_BANNER = 'content_banner';

    protected const MESSAGE_BANNER_NOT_FOUND = '<!-- Content Banner with ID %s not found. -->';
    protected const MESSAGE_BANNER_WRONG_TYPE = '<!-- %s could not be rendered for content item with ID %s. -->';
    protected const MESSAGE_BANNER_WRONG_TEMPLATE = '<!-- %s is not supported name of template. -->';

    protected const TEMPLATE_IDENTIFIER_DEFAULT = 'default';
    protected const TEMPLATE_IDENTIFIER_TOP_TITLE = 'top-title';

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
        return static::TWIG_FUNCTION_NAME_CONTNET_BANNER;
    }

    /**
     * @return callable
     */
    protected function getFunction(): callable
    {
        return function (int $idContent, string $templateIdentifier): ?string {
            if (!isset($this->getAvailableTemplates()[$templateIdentifier])) {
                return $this->getMessageBannerWrongTemplate($templateIdentifier);
            }
            try {
                $contentBannerTypeTransfer = $this->contentBannerClient->findBannerTypeById($idContent, $this->localeName);

                if (!$contentBannerTypeTransfer) {
                    return $this->getMessageBannerNotFound($idContent);
                }
            } catch (MissingBannerTermException $e) {
                return $this->getMessageBannerWrongType($idContent);
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
            static::TEMPLATE_IDENTIFIER_DEFAULT => '@ContentBannerWidget/views/banner/banner.twig',
            static::TEMPLATE_IDENTIFIER_TOP_TITLE => '@ContentBannerWidget/views/banner/banner-top-title.twig',
        ];
    }

    /**
     * @param int $idContent
     *
     * @return string
     */
    protected function getMessageBannerNotFound(int $idContent)
    {
        return sprintf(static::MESSAGE_BANNER_NOT_FOUND, $idContent);
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
     * @param int $idContent
     *
     * @return string
     */
    protected function getMessageBannerWrongType(int $idContent)
    {
        return sprintf(static::MESSAGE_BANNER_WRONG_TYPE, static::TWIG_FUNCTION_NAME_CONTNET_BANNER, $idContent);
    }
}
