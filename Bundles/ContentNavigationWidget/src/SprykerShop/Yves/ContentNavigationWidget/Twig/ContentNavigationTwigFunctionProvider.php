<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentNavigationWidget\Twig;

use ArrayObject;
use DateTime;
use Generated\Shared\Transfer\NavigationStorageTransfer;
use Spryker\Client\ContentNavigation\Exception\MissingNavigationTermException;
use Spryker\Shared\Twig\TwigFunctionProvider;
use SprykerShop\Yves\ContentNavigationWidget\ContentNavigationWidgetConfig;
use SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToContentNavigationClientInterface;
use SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToNavigationStorageClientInterface;
use Twig\Environment;

class ContentNavigationTwigFunctionProvider extends TwigFunctionProvider
{
    /**
     * @uses \Spryker\Shared\ContentNavigation\ContentNavigationWidgetConfig::TWIG_FUNCTION_NAME
     *
     * @var string
     */
    protected const TWIG_FUNCTION_NAME_CONTENT_NAVIGATION = 'content_navigation';

    /**
     * @var string
     */
    protected const MESSAGE_NAVIGATION_NOT_FOUND = '<b>Content Navigation with key %s not found.</b>';

    /**
     * @var string
     */
    protected const MESSAGE_NAVIGATION_WRONG_TYPE = '<b>Content Navigation could not be rendered because the content item with key %s is not an navigation.</b>';

    /**
     * @var string
     */
    protected const MESSAGE_NAVIGATION_WRONG_TEMPLATE = '<b>"%s" is not supported name of template.</b>';

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
     * @var \SprykerShop\Yves\ContentNavigationWidget\ContentNavigationWidgetConfig
     */
    protected $contentNavigationWidgetConfig;

    /**
     * @param \Twig\Environment $twig
     * @param string $localeName
     * @param \SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToContentNavigationClientInterface $contentNavigationClient
     * @param \SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToNavigationStorageClientInterface $navigationStorageClient
     * @param \SprykerShop\Yves\ContentNavigationWidget\ContentNavigationWidgetConfig $contentNavigationWidgetConfig
     */
    public function __construct(
        Environment $twig,
        string $localeName,
        ContentNavigationWidgetToContentNavigationClientInterface $contentNavigationClient,
        ContentNavigationWidgetToNavigationStorageClientInterface $navigationStorageClient,
        ContentNavigationWidgetConfig $contentNavigationWidgetConfig
    ) {
        $this->twig = $twig;
        $this->localeName = $localeName;
        $this->contentNavigationClient = $contentNavigationClient;
        $this->navigationStorageClient = $navigationStorageClient;
        $this->contentNavigationWidgetConfig = $contentNavigationWidgetConfig;
    }

    /**
     * @return string
     */
    public function getFunctionName(): string
    {
        return static::TWIG_FUNCTION_NAME_CONTENT_NAVIGATION;
    }

    /**
     * @return callable
     */
    public function getFunction(): callable
    {
        return function (string $contentKey, string $templateIdentifier) {
            $availableTemplate = $this->findTemplate($templateIdentifier);
            if (!$availableTemplate) {
                return $this->getMessageNavigationWrongTemplate($templateIdentifier);
            }
            try {
                $contentNavigationTypeTransfer = $this->contentNavigationClient->executeNavigationTypeByKey($contentKey, $this->localeName);
                if (!$contentNavigationTypeTransfer) {
                    return $this->getMessageNavigationNotFound($contentKey);
                }
            } catch (MissingNavigationTermException $e) {
                return $this->getMessageNavigationWrongType($contentKey);
            }

            $navigationStorageTransfer = $this->navigationStorageClient->findNavigationTreeByKey(
                $contentNavigationTypeTransfer->getNavigationKey(),
                $this->localeName,
            );

            if (!$navigationStorageTransfer) {
                return $this->getMessageNavigationNotFound($contentKey);
            }

            if (!$navigationStorageTransfer->getIsActive()) {
                return '';
            }

            $navigationStorageTransfer = $this->optimizeNavigationStorageNodes($navigationStorageTransfer);

            return $this->twig->render(
                $availableTemplate,
                ['navigation' => $navigationStorageTransfer],
            );
        };
    }

    /**
     * @param string $templateIdentifier
     *
     * @return string|null
     */
    protected function findTemplate(string $templateIdentifier): ?string
    {
        $availableTemplateList = $this->contentNavigationWidgetConfig->getAvailableTemplateList();

        return $availableTemplateList[$templateIdentifier] ?? null;
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

    /**
     * @param \Generated\Shared\Transfer\NavigationStorageTransfer $navigationStorageTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationStorageTransfer
     */
    protected function optimizeNavigationStorageNodes(NavigationStorageTransfer $navigationStorageTransfer): NavigationStorageTransfer
    {
        $now = new DateTime();

        $optimizedNavigationNodeStorageTransfers = new ArrayObject();

        foreach ($navigationStorageTransfer->getNodes() as $navigationNodeStorageTransfer) {
            $isValidFrom = $navigationNodeStorageTransfer->getValidFrom() === null || new DateTime($navigationNodeStorageTransfer->getValidFrom()) <= $now;
            $isValidTo = $navigationNodeStorageTransfer->getValidTo() === null || new DateTime($navigationNodeStorageTransfer->getValidTo()) >= $now;
            $isActiveAndValid = $navigationNodeStorageTransfer->getIsActive() && $isValidFrom && $isValidTo;
            $hasChildren = $navigationNodeStorageTransfer->getChildren()->count() > 0;

            $navigationNodeStorageTransfer->setIsValidFrom($isValidFrom);
            $navigationNodeStorageTransfer->setIsValidTo($isValidTo);
            $navigationNodeStorageTransfer->setIsActiveAndValid($isActiveAndValid);
            $navigationNodeStorageTransfer->setHasChildren($hasChildren);

            $optimizedNavigationNodeStorageTransfers->append($navigationNodeStorageTransfer);
        }
        $navigationStorageTransfer->setNodes($optimizedNavigationNodeStorageTransfers);

        return $navigationStorageTransfer;
    }
}
