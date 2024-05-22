<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CommentWidget\Checker\CommentChecker;
use SprykerShop\Yves\CommentWidget\Checker\CommentCheckerInterface;
use SprykerShop\Yves\CommentWidget\Dependency\Client\CommentWidgetToCommentClientInterface;
use SprykerShop\Yves\CommentWidget\Dependency\Client\CommentWidgetToCustomerClientInterface;
use SprykerShop\Yves\CommentWidget\Expander\CommentThreadExpander;
use SprykerShop\Yves\CommentWidget\Expander\CommentThreadExpanderInterface;
use SprykerShop\Yves\CommentWidget\Operation\CommentOperation;
use SprykerShop\Yves\CommentWidget\Operation\CommentOperationInterface;
use SprykerShop\Yves\CommentWidget\Preparer\CommentThreadPreparer;
use SprykerShop\Yves\CommentWidget\Preparer\CommentThreadPreparerInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * @method \SprykerShop\Yves\CommentWidget\CommentWidgetConfig getConfig()
 */
class CommentWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CommentWidget\Operation\CommentOperationInterface
     */
    public function createCommentOperation(): CommentOperationInterface
    {
        return new CommentOperation(
            $this->getCommentThreadAfterOperationStrategyPlugins(),
        );
    }

    /**
     * @return \SprykerShop\Yves\CommentWidget\Checker\CommentCheckerInterface
     */
    public function createCommentChecker(): CommentCheckerInterface
    {
        return new CommentChecker();
    }

    /**
     * @return \SprykerShop\Yves\CommentWidget\Preparer\CommentThreadPreparerInterface
     */
    public function createCommentThreadPreparer(): CommentThreadPreparerInterface
    {
        return new CommentThreadPreparer();
    }

    /**
     * @return \SprykerShop\Yves\CommentWidget\Expander\CommentThreadExpanderInterface
     */
    public function createCommentThreadExpander(): CommentThreadExpanderInterface
    {
        return new CommentThreadExpander();
    }

    /**
     * @return \SprykerShop\Yves\CommentWidget\Dependency\Client\CommentWidgetToCommentClientInterface
     */
    public function getCommentClient(): CommentWidgetToCommentClientInterface
    {
        return $this->getProvidedDependency(CommentWidgetDependencyProvider::CLIENT_COMMENT);
    }

    /**
     * @return \SprykerShop\Yves\CommentWidget\Dependency\Client\CommentWidgetToCustomerClientInterface
     */
    public function getCustomerClient(): CommentWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(CommentWidgetDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return array<\SprykerShop\Yves\CommentWidgetExtension\Dependency\Plugin\CommentThreadAfterOperationStrategyPluginInterface>
     */
    public function getCommentThreadAfterOperationStrategyPlugins(): array
    {
        return $this->getProvidedDependency(CommentWidgetDependencyProvider::PLUGINS_COMMENT_THREAD_AFTER_OPERATION_STRATEGY);
    }

    /**
     * @return \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface
     */
    public function getCsrfTokenManager(): CsrfTokenManagerInterface
    {
        return $this->getProvidedDependency(CommentWidgetDependencyProvider::SERVICE_FORM_CSRF_PROVIDER);
    }
}
