<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentProductWidget\Plugin;

use Spryker\Client\ContentProduct\Exception\InvalidProductAbstractListTypeException;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \SprykerShop\Yves\ContentProductWidget\ContentProductWidgetFactory getFactory()
 */
class ContentProductAbstractListTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    public const FUNCTION_CMS_CONTENT_PRODUCT_ABSTRACT = 'content_product_abstract_list';

    public const CONTENT_NOT_FOUND_MESSAGE_TEMPLATE = 'Content Product Abstract with ID %s not found.';
    public const CONTENT_WRONG_TYPE_TEMPLATE = '%s widget cannot display for ID %s.';
    public const CONTENT_NOT_SUPPORTED_MESSAGE_TEMPLATE = '%s is not supported name of template .';

    protected const DEFAULT_TEMPLATE_IDENTIFIER = 'default';
    protected const TOP_TITLE_TEMPLATE_IDENTIFIER = 'top-title';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $twig->addFunction(
            static::FUNCTION_CMS_CONTENT_PRODUCT_ABSTRACT,
            new TwigFunction(static::FUNCTION_CMS_CONTENT_PRODUCT_ABSTRACT, function (int $idContent, string $templateIdentifier) use ($twig) {
                try {
                    $productAbstractViewCollection = $this->getFactory()
                        ->createContentProductAbstractReader()
                        ->getProductAbstractCollection($idContent, $this->getLocale());
                } catch (InvalidProductAbstractListTypeException $exception) {
                    return '<!--' . sprintf(static::CONTENT_WRONG_TYPE_TEMPLATE, static::FUNCTION_CMS_CONTENT_PRODUCT_ABSTRACT, $idContent) . '-->';
                }

                if ($productAbstractViewCollection === null) {
                    return '<!--' . sprintf(static::CONTENT_NOT_FOUND_MESSAGE_TEMPLATE, $idContent) . '-->';
                }

                if (!isset($this->getAvailableTemplates()[$templateIdentifier])) {
                    return '<!--' . sprintf(static::CONTENT_NOT_SUPPORTED_MESSAGE_TEMPLATE, $templateIdentifier) . '-->';
                }

                return $twig->render(
                    $this->getAvailableTemplates()[$templateIdentifier],
                    [
                        'productAbstractViewCollection' => $productAbstractViewCollection,
                    ]
                );
            },
            ['is_safe' => ['html']])
        );

        return $twig;
    }

    /**
     * @return array
     */
    public function getAvailableTemplates(): array
    {
        return [
            static::DEFAULT_TEMPLATE_IDENTIFIER => '@ContentProductWidget/views/cms-product-abstract/cms-product-abstract.twig',
            static::TOP_TITLE_TEMPLATE_IDENTIFIER => '@ContentProductWidget/views/cms-product-abstract/cms-product-abstract-top.twig',
        ];
    }
}
