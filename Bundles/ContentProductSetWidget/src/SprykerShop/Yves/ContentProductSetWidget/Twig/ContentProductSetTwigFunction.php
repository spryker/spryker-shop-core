<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentProductSetWidget\Twig;

use Spryker\Client\ContentProductSet\Exception\InvalidProductSetTermException;
use Spryker\Shared\Twig\TwigFunction;
use SprykerShop\Yves\ContentProductSetWidget\Reader\ContentProductAbstractReaderInterface;
use SprykerShop\Yves\ContentProductSetWidget\Reader\ContentProductSetReaderInterface;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

/**
 * @method \SprykerShop\Yves\ContentProductSetWidget\ContentProductSetWidgetFactory getFactory()
 */
class ContentProductSetTwigFunction extends TwigFunction
{
    /**
     * @uses \Spryker\Shared\ContentProductSet\ContentProductSetConfig::TWIG_FUNCTION_NAME
     */
    protected const FUNCTION_CONTENT_PRODUCT_SET = 'content_product_set';

    /**
     * @uses \Spryker\Shared\ContentProductSet\ContentProductSetConfig::WIDGET_TEMPLATE_IDENTIFIER_DEFAULT
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_DEFAULT = 'default';

    /**
     * @uses \Spryker\Shared\ContentProductSet\ContentProductSetConfig::WIDGET_TEMPLATE_IDENTIFIER_CART_BUTTON_BOTTOM
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_CART_BUTTON_BOTTOM = 'cart-button-btm';

    protected const MESSAGE_CONTENT_PRODUCT_SET_NOT_FOUND = '<strong>Content product set with ID %s not found.</strong>';
    protected const MESSAGE_WRONG_CONTENT_PRODUCT_SET_TYPE = '<strong>Content product set widget could not be rendered because the content item with ID %s is not a product set.</strong>';
    protected const MESSAGE_NOT_SUPPORTED_TEMPLATE = '<strong>"%s" is not supported name of template.</strong>';

    /**
     * @var \Twig\Environment
     */
    protected $twig;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var string
     */
    protected $localeName;

    /**
     * @var \SprykerShop\Yves\ContentProductSetWidget\Reader\ContentProductSetReaderInterface
     */
    protected $contentProductSetReader;

    /**
     * @var \SprykerShop\Yves\ContentProductSetWidget\Reader\ContentProductAbstractReaderInterface
     */
    protected $contentProductAbstractReader;

    /**
     * @param \Twig\Environment $twig
     * @param string $localeName
     * @param \SprykerShop\Yves\ContentProductSetWidget\Reader\ContentProductSetReaderInterface $contentProductSetReader
     * @param \SprykerShop\Yves\ContentProductSetWidget\Reader\ContentProductAbstractReaderInterface $contentProductAbstractReader
     */
    public function __construct(
        Environment $twig,
        string $localeName,
        ContentProductSetReaderInterface $contentProductSetReader,
        ContentProductAbstractReaderInterface $contentProductAbstractReader
    ) {
        parent::__construct();

        $this->twig = $twig;
        $this->localeName = $localeName;
        $this->contentProductSetReader = $contentProductSetReader;
        $this->contentProductAbstractReader = $contentProductAbstractReader;
    }

    /**
     * @return string
     */
    protected function getFunctionName(): string
    {
        return static::FUNCTION_CONTENT_PRODUCT_SET;
    }

    /**
     * @return callable
     */
    public function getFunction(): callable
    {
        return function (array $context, int $idContent, string $templateIdentifier): string {
            if (!isset($this->getAvailableTemplates()[$templateIdentifier])) {
                return $this->getMessageProductSetWrongTemplate($templateIdentifier);
            }

            try {
                $productSetDataStorageTransfer = $this->contentProductSetReader
                    ->findProductSet($idContent, $this->localeName);
            } catch (InvalidProductSetTermException $exception) {
                return $this->getMessageProductSetWrongType($idContent);
            }

            if (!$productSetDataStorageTransfer) {
                return $this->getMessageProductSetNotFound($idContent);
            }

            $productAbstractViewCollection = $this->contentProductAbstractReader
                ->findProductAbstractCollection($productSetDataStorageTransfer, $this->getRequest($context), $this->localeName);

            return $this->twig->render(
                $this->getAvailableTemplates()[$templateIdentifier],
                [
                    'productSet' => $productSetDataStorageTransfer,
                    'productViews' => $productAbstractViewCollection,
                ]
            );
        };
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            'needs_context' => true,
            'is_safe' => ['html'],
        ];
    }

    /**
     * @return string[]
     */
    protected function getAvailableTemplates(): array
    {
        return [
            static::WIDGET_TEMPLATE_IDENTIFIER_DEFAULT => '@ContentProductSetWidget/views/content-product-set/content-product-set.twig',
            static::WIDGET_TEMPLATE_IDENTIFIER_CART_BUTTON_BOTTOM => '@ContentProductSetWidget/views/content-product-set/content-product-set-cart-button-btm.twig',
        ];
    }

    /**
     * @param int $idContent
     *
     * @return string
     */
    protected function getMessageProductSetNotFound(int $idContent): string
    {
        return sprintf(static::MESSAGE_CONTENT_PRODUCT_SET_NOT_FOUND, $idContent);
    }

    /**
     * @param string $templateIdentifier
     *
     * @return string
     */
    protected function getMessageProductSetWrongTemplate(string $templateIdentifier): string
    {
        return sprintf(static::MESSAGE_NOT_SUPPORTED_TEMPLATE, $templateIdentifier);
    }

    /**
     * @param int $idContent
     *
     * @return string
     */
    protected function getMessageProductSetWrongType(int $idContent): string
    {
        return sprintf(static::MESSAGE_WRONG_CONTENT_PRODUCT_SET_TYPE, $idContent);
    }

    /**
     * @param array $context
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest(array $context): Request
    {
        return $context['app']['request'];
    }
}
