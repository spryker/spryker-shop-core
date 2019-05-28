<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentFileWidget\Twig;

use Spryker\Client\ContentFile\Exception\InvalidFileListTermException;
use Spryker\Shared\Twig\TwigFunction;
use SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToContentFileClientInterface;
use SprykerShop\Yves\ContentFileWidget\Reader\ContentFileReaderInterface;
use Twig\Environment;

class ContentFileListTwigFunction extends TwigFunction
{
    /**
     * @uses \Spryker\Shared\ContentFile\ContentFileConfig::TWIG_FUNCTION_NAME
     */
    protected const FUNCTION_CONTENT_FILE_LIST = 'content_file_list';

    protected const MESSAGE_CONTENT_FILE_LIST_NOT_FOUND = '<strong>Content file list with ID %s not found.</strong>';
    protected const MESSAGE_WRONG_CONTENT_FILE_LIST_TYPE = '<strong>Content file list widget could not be rendered because the content item with ID %s is not an file list.</strong>';
    protected const MESSAGE_NOT_SUPPORTED_TEMPLATE = '<strong>"%s" is not supported name of template.</strong>';

    /**
     * @uses \Spryker\Shared\ContentFile\ContentFileConfig::WIDGET_TEMPLATE_IDENTIFIER_TEXT_LINK
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_TEXT_LINK = 'text-link';

    /**
     * @uses \Spryker\Shared\ContentFile\ContentFileConfig::WIDGET_TEMPLATE_IDENTIFIER_FILE_ICON_AND_SIZE
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_FILE_ICON_AND_SIZE = 'file-icon-and-size';

    /**
     * @var \Twig\Environment
     */
    protected $twig;

    /**
     * @var string
     */
    protected $localeName;

    /**
     * @var \SprykerShop\Yves\ContentFileWidget\Reader\ContentFileReaderInterface
     */
    protected $contentFileReader;

    /**
     * @var \SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToContentFileClientInterface
     */
    protected $contentFileClient;

    /**
     * @param \Twig\Environment $twig
     * @param string $localeName
     * @param \SprykerShop\Yves\ContentFileWidget\Reader\ContentFileReaderInterface $contentFileReader
     * @param \SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToContentFileClientInterface $contentFileClient
     */
    public function __construct(
        Environment $twig,
        string $localeName,
        ContentFileReaderInterface $contentFileReader,
        ContentFileWidgetToContentFileClientInterface $contentFileClient
    ) {
        parent::__construct();

        $this->twig = $twig;
        $this->localeName = $localeName;
        $this->contentFileReader = $contentFileReader;
        $this->contentFileClient = $contentFileClient;
    }

    /**
     * @return string
     */
    protected function getFunctionName(): string
    {
        return static::FUNCTION_CONTENT_FILE_LIST;
    }

    /**
     * @return callable
     */
    public function getFunction(): callable
    {
        return function (int $idContent, string $templateIdentifier): ?string {

            if (!isset($this->getAvailableTemplates()[$templateIdentifier])) {
                return $this->getMessageContentFileListWrongTemplate($templateIdentifier);
            }

            try {
                $contentFileListTypeTransfer = $this->contentFileClient->executeFileListTypeById($idContent, $this->localeName);
            } catch (InvalidFileListTermException $exception) {
                return $this->getMessageContentFileListWrongType($idContent);
            }

            if (!$contentFileListTypeTransfer) {
                return $this->getMessageContentFileListNotFound($idContent);
            }

            $fileViewCollection = $this->contentFileReader
                ->getFileCollection($contentFileListTypeTransfer, $this->localeName);

            return $this->twig->render(
                $this->getAvailableTemplates()[$templateIdentifier],
                [
                    'fileViewCollection' => $fileViewCollection,
                ]
            );
        };
    }

    /**
     * @return array
     */
    protected function getAvailableTemplates(): array
    {
        return [
            static::WIDGET_TEMPLATE_IDENTIFIER_TEXT_LINK => '@ContentFileWidget/views/content-file/content-file-text-link.twig',
            static::WIDGET_TEMPLATE_IDENTIFIER_FILE_ICON_AND_SIZE => '@ContentFileWidget/views/content-file/content-file-icon-and-size.twig',
        ];
    }

    /**
     * @param int $idContent
     *
     * @return string
     */
    protected function getMessageContentFileListNotFound(int $idContent): string
    {
        return sprintf(static::MESSAGE_CONTENT_FILE_LIST_NOT_FOUND, $idContent);
    }

    /**
     * @param string $templateIdentifier
     *
     * @return string
     */
    protected function getMessageContentFileListWrongTemplate(string $templateIdentifier): string
    {
        return sprintf(static::MESSAGE_NOT_SUPPORTED_TEMPLATE, $templateIdentifier);
    }

    /**
     * @param int $idContent
     *
     * @return string
     */
    protected function getMessageContentFileListWrongType(int $idContent): string
    {
        return sprintf(static::MESSAGE_WRONG_CONTENT_FILE_LIST_TYPE, $idContent);
    }
}
