<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentProductWidget\Twig;

use Spryker\Client\ContentProduct\Exception\InvalidProductAbstractListTermException;
use Spryker\Shared\Twig\TwigFunction;
use SprykerShop\Yves\ContentProductWidget\Reader\ContentProductAbstractReaderInterface;
use Twig\Environment;

/**
 * @method \SprykerShop\Yves\ContentProductWidget\ContentProductWidgetFactory getFactory()
 */
class ContentProductAbstractListTwigFunction extends TwigFunction
{
    /**
     * @uses \Spryker\Shared\ContentProduct\ContentProductConfig::TWIG_FUNCTION_NAME
     */
    protected const FUNCTION_CONTENT_PRODUCT_ABSTRACT_LIST = 'content_product_abstract_list';

    protected const MESSAGE_CONTENT_PRODUCT_ABSTRACT_LIST_NOT_FOUND = '<strong>Content product abstract list with content key "%s" not found.</strong>';
    protected const MESSAGE_WRONG_CONTENT_PRODUCT_ABSTRACT_LIST_TYPE = '<strong>Content product abstract list widget could not be rendered because the content item with key "%s" is not an abstract product list.</strong>';
    protected const MESSAGE_NOT_SUPPORTED_TEMPLATE = '<strong>"%s" is not supported name of template.</strong>';

    /**
     * @uses \Spryker\Shared\ContentProduct\ContentProductConfig::WIDGET_TEMPLATE_IDENTIFIER_BOTTOM_TITLE
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_BOTTOM_TITLE = 'bottom-title';

    /**
     * @uses \Spryker\Shared\ContentProduct\ContentProductConfig::WIDGET_TEMPLATE_IDENTIFIER_TOP_TITLE
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
        return function (string $contentKey, string $templateIdentifier): ?string {

            if (!isset($this->getAvailableTemplates()[$templateIdentifier])) {
                return $this->getMessageProductAbstractWrongTemplate($templateIdentifier);
            }

            try {
                $productAbstractViewCollection = $this->contentProductAbstractReader
                    ->findProductAbstractCollection($contentKey, $this->localeName);
            } catch (InvalidProductAbstractListTermException $exception) {
                return $this->getMessageProductAbstractWrongType($contentKey);
            }

            if ($productAbstractViewCollection === null) {
                return $this->getMessageProductAbstractNotFound($contentKey);
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
            static::WIDGET_TEMPLATE_IDENTIFIER_BOTTOM_TITLE => '@ContentProductWidget/views/cms-product-abstract-list/cms-product-abstract-list.twig',
            static::WIDGET_TEMPLATE_IDENTIFIER_TOP_TITLE => '@ContentProductWidget/views/cms-product-abstract-list-alternative/cms-product-abstract-list-alternative.twig',
        ];
    }

    /**
     * @param string $contentKey
     *
     * @return string
     */
    protected function getMessageProductAbstractNotFound(string $contentKey): string
    {
        return sprintf(static::MESSAGE_CONTENT_PRODUCT_ABSTRACT_LIST_NOT_FOUND, $contentKey);
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
     * @param string $contentKey
     *
     * @return string
     */
    protected function getMessageProductAbstractWrongType(string $contentKey): string
    {
        return sprintf(static::MESSAGE_WRONG_CONTENT_PRODUCT_ABSTRACT_LIST_TYPE, $contentKey);
    }
}
