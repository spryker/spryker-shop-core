<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentProductWidget\Twig;

use Spryker\Client\ContentProduct\Exception\InvalidProductAbstractListTypeException;
use Spryker\Shared\Twig\TwigFunction;
use SprykerShop\Yves\ContentProductWidget\Reader\ContentProductAbstractReaderInterface;
use Twig\Environment;

/**
 * @method \SprykerShop\Yves\ContentProductWidget\ContentProductWidgetFactory getFactory()
 */
class ContentProductAbstractListTwigFunction extends TwigFunction
{
    protected const FUNCTION_CONTENT_PRODUCT_ABSTRACT_LIST = 'content_product_abstract_list';

    protected const MESSAGE_CONTENT_PRODUCT_ABSTRACT_LIST_NOT_FOUND = '<!-- Content product abstract list with ID %s not found. -->';
    protected const MESSAGE_WRONG_CONTENT_PRODUCT_ABSTRACT_LIST_TYPE = '<!-- %s could not be rendered for content item with ID %s. -->';
    protected const MESSAGE_NOT_SUPPORTED_TEMPLATE = '<!-- %s is not supported name of template. -->';

    protected const DEFAULT_TEMPLATE_IDENTIFIER = 'default';
    protected const TOP_TITLE_TEMPLATE_IDENTIFIER = 'top-title';

    /**
     * @var \Twig\Environment
     */
    protected $twig;

    /**
     * @var string
     */
    protected $localeName;

    /**
     * @var \SprykerShop\Yves\ContentProductWidget\Reader\ContentProductAbstractReaderInterface
     */
    protected $contentProductAbstractReader;

    /**
     * @param \Twig\Environment $twig
     * @param string $localeName
     * @param \SprykerShop\Yves\ContentProductWidget\Reader\ContentProductAbstractReaderInterface $contentProductAbstractReader
     */
    public function __construct(
        Environment $twig,
        string $localeName,
        ContentProductAbstractReaderInterface $contentProductAbstractReader
    ) {
        parent::__construct();

        $this->twig = $twig;
        $this->localeName = $localeName;
        $this->contentProductAbstractReader = $contentProductAbstractReader;
    }

    /**
     * @return string
     */
    protected function getFunctionName(): string
    {
        return static::FUNCTION_CONTENT_PRODUCT_ABSTRACT_LIST;
    }

    /**
     * @return callable
     */
    public function getFunction(): callable
    {
        return function (int $idContent, string $templateIdentifier): ?string {
            try {
                $productAbstractViewCollection = $this->contentProductAbstractReader
                    ->getProductAbstractCollection($idContent, $this->localeName);
            } catch (InvalidProductAbstractListTypeException $exception) {
                return $this->getMessageProductAbstractWrongType($idContent);
            }

            if ($productAbstractViewCollection === null) {
                return $this->getMessageProductAbstractNotFound($idContent);
            }

            if (!isset($this->getAvailableTemplates()[$templateIdentifier])) {
                return $this->getMessageProductAbstractWrongTemplate($templateIdentifier);
            }

            return $this->twig->render(
                $this->getAvailableTemplates()[$templateIdentifier],
                [
                    'productAbstractViewCollection' => $productAbstractViewCollection,
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
            static::DEFAULT_TEMPLATE_IDENTIFIER => '@ContentProductWidget/views/cms-product-abstract/cms-product-abstract.twig',
            static::TOP_TITLE_TEMPLATE_IDENTIFIER => '@ContentProductWidget/views/cms-product-abstract/cms-product-abstract-top.twig',
        ];
    }

    /**
     * @param int $idContent
     *
     * @return string
     */
    protected function getMessageProductAbstractNotFound(int $idContent): string
    {
        return sprintf(static::MESSAGE_CONTENT_PRODUCT_ABSTRACT_LIST_NOT_FOUND, $idContent);
    }

    /**
     * @param string $templateIdentifier
     *
     * @return string
     */
    protected function getMessageProductAbstractWrongTemplate(string $templateIdentifier): string
    {
        return sprintf(static::MESSAGE_NOT_SUPPORTED_TEMPLATE, $templateIdentifier);
    }

    /**
     * @param int $idContent
     *
     * @return string
     */
    protected function getMessageProductAbstractWrongType(int $idContent): string
    {
        return sprintf(static::MESSAGE_WRONG_CONTENT_PRODUCT_ABSTRACT_LIST_TYPE, static::FUNCTION_CONTENT_PRODUCT_ABSTRACT_LIST, $idContent);
    }
}
