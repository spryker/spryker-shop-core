<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentNavigationWidget\Twig;

use Spryker\Client\ContentNavigation\Exception\MissingNavigationTermException;
use Spryker\Shared\Twig\TwigFunction;
use SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToContentNavigationClientInterface;
use SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToNavigationStorageClientInterface;
use Twig\Environment;

class ContentNavigationTwigFunction extends TwigFunction
{
    /**
     * @uses \Spryker\Shared\ContentNavigation\ContentNavigationConfig::TWIG_FUNCTION_NAME
     */
    protected const TWIG_FUNCTION_NAME_CONTENT_NAVIGATION = 'content_navigation';

    protected const MESSAGE_NAVIGATION_NOT_FOUND = '<b>Content Navigation with key %s not found.</b>';
    protected const MESSAGE_NAVIGATION_WRONG_TYPE = '<b>Content Navigation could not be rendered because the content item with key %s is not an navigation.</b>';
    protected const MESSAGE_NAVIGATION_WRONG_TEMPLATE = '<b>"%s" is not supported name of template.</b>';

    /**
     * @uses \Spryker\Shared\ContentNavigation\ContentNavigationConfig::WIDGET_TEMPLATE_IDENTIFIER_TREE_INLINE
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_TREE_INLINE = 'tree-inline';

    /**
     * @uses \Spryker\Shared\ContentNavigation\ContentNavigationConfig::WIDGET_TEMPLATE_IDENTIFIER_TREE
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_TREE = 'tree';

    /**
     * @uses \Spryker\Shared\ContentNavigation\ContentNavigationConfig::WIDGET_TEMPLATE_IDENTIFIER_TREE_INLINE
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_LIST_INLINE = 'list-inline';

    /**
     * @uses \Spryker\Shared\ContentNavigation\ContentNavigationConfig::WIDGET_TEMPLATE_IDENTIFIER_TREE_INLINE
     */
    protected const WIDGET_TEMPLATE_IDENTIFIER_LIST = 'list';

    /**
     * @var \Twig\Environment
     */
    protected $twig;

    /**
     * @var string
     */
    protected $localeName;

    /**
     * @var \SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToContentNavigationClientInterface
     */
    protected $contentNavigationClient;

    /**
     * @var \SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToNavigationStorageClientInterface
     */
    protected $navigationStorageClient;

    /**
     * @param \Twig\Environment $twig
     * @param string $localeName
     * @param \SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToContentNavigationClientInterface $contentNavigationClient
     * @param \SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToNavigationStorageClientInterface $navigationStorageClient
     */
    public function __construct(
        Environment $twig,
        string $localeName,
        ContentNavigationWidgetToContentNavigationClientInterface $contentNavigationClient,
        ContentNavigationWidgetToNavigationStorageClientInterface $navigationStorageClient
    ) {
        parent::__construct();
        $this->twig = $twig;
        $this->localeName = $localeName;
        $this->contentNavigationClient = $contentNavigationClient;
        $this->navigationStorageClient = $navigationStorageClient;
    }

    /**
     * @return string
     */
    protected function getFunctionName(): string
    {
        return static::TWIG_FUNCTION_NAME_CONTENT_NAVIGATION;
    }

    /**
     * @return callable
     */
    protected function getFunction(): callable
    {
        return function (string $contentKey, string $templateIdentifier): ?string {
            if (!isset($this->getAvailableTemplates()[$templateIdentifier])) {
                return $this->getMessageNavigationWrongTemplate($templateIdentifier);
            }
            try {
                $contentNavigationTypeTransfer = $this->contentNavigationClient->executeNavigationTypeByKey($contentKey, $this->localeName);
                if (!$contentNavigationTypeTransfer) {
                    return $this->getMessageNavigationNotFound($contentKey);
                }

                $navigationStorageTransfer = $this->navigationStorageClient->findNavigationTreeByKey(
                    $contentNavigationTypeTransfer->getNavigationKey(),
                    $this->localeName
                );
            } catch (MissingNavigationTermException $e) {
                return $this->getMessageNavigationWrongType($contentKey);
            }

            return $this->twig->render(
                $this->getAvailableTemplates()[$templateIdentifier],
                ['navigation' => $navigationStorageTransfer]
            );
        };
    }

    /**
     * @return array
     */
    protected function getAvailableTemplates(): array
    {
        return [
            static::WIDGET_TEMPLATE_IDENTIFIER_TREE_INLINE => '@ContentNavigationWidget/views/navigation/tree-inline.twig',
            static::WIDGET_TEMPLATE_IDENTIFIER_TREE => '@ContentNavigationWidget/views/navigation/tree.twig',
            static::WIDGET_TEMPLATE_IDENTIFIER_LIST_INLINE => '@ContentNavigationWidget/views/navigation/list-inline.twig',
            static::WIDGET_TEMPLATE_IDENTIFIER_LIST => '@ContentNavigationWidget/views/navigation/list.twig',
        ];
    }

    /**
     * @param string $contentKey
     *
     * @return string
     */
    protected function getMessageNavigationNotFound(string $contentKey): string
    {
        return sprintf(static::MESSAGE_NAVIGATION_NOT_FOUND, $contentKey);
    }

    /**
     * @param string $templateIdentifier
     *
     * @return string
     */
    protected function getMessageNavigationWrongTemplate(string $templateIdentifier): string
    {
        return sprintf(static::MESSAGE_NAVIGATION_WRONG_TEMPLATE, $templateIdentifier);
    }

    /**
     * @param string $contentKey
     *
     * @return string
     */
    protected function getMessageNavigationWrongType(string $contentKey): string
    {
        return sprintf(static::MESSAGE_NAVIGATION_WRONG_TYPE, $contentKey);
    }
}
