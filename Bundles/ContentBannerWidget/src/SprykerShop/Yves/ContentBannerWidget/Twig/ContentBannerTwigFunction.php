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

    protected const MESSAGE_BANNER_NOT_FOUND = 'Content Banner with ID %s not found.';
    protected const MESSAGE_BANNER_WRONG_TYPE = '%s widget cannot display for ID %s.';
    protected const MESSAGE_BANNER_WRONG_TEMPLATE = '%s is not supported name of template .';

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
        return function (int $idContent, string $templateIdentifier) {
            if (!isset($this->getAvailableTemplates()[$templateIdentifier])) {
                return $this->formatErrorMessage(sprintf(static::MESSAGE_BANNER_WRONG_TEMPLATE, $templateIdentifier));
            }
            try {
                $contentBannerTypeTransfer = $this->contentBannerClient->findBannerTypeById($idContent, $this->localeName);

                if (!$contentBannerTypeTransfer) {
                    return $this->formatErrorMessage(sprintf(static::MESSAGE_BANNER_NOT_FOUND, $idContent));
                }
            } catch (MissingBannerTermException $e) {
                return $this->formatErrorMessage(sprintf(static::MESSAGE_BANNER_WRONG_TYPE, static::TWIG_FUNCTION_NAME_CONTNET_BANNER, $idContent));
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
     * @param string $message
     *
     * @return string
     */
    protected function formatErrorMessage(string $message): string
    {
        return '<!-- ' . $message . ' -->';
    }
}
