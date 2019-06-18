<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget;

use Generated\Shared\Transfer\CommentTransfer;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CommentWidget\Dependency\Client\CommentWidgetToCommentClientInterface;
use SprykerShop\Yves\CommentWidget\Dependency\Client\CommentWidgetToCustomerClientInterface;
use SprykerShop\Yves\CommentWidget\Form\CommentForm;
use SprykerShop\Yves\CommentWidget\Form\DataProvider\CommentFormDataProvider;
use SprykerShop\Yves\CommentWidget\Operation\CommentOperation;
use SprykerShop\Yves\CommentWidget\Operation\CommentOperationInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\CommentWidget\CommentWidgetConfig getConfig()
 */
class CommentWidgetFactory extends AbstractFactory
{
    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $commentTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCommentForm(CommentTransfer $commentTransfer): FormInterface
    {
        return $this->getFormFactory()->create(CommentForm::class, $commentTransfer);
    }

    /**
     * @return \SprykerShop\Yves\CommentWidget\Form\DataProvider\CommentFormDataProvider
     */
    public function createCommentFormDataProvider(): CommentFormDataProvider
    {
        return new CommentFormDataProvider();
    }

    /**
     * @return \SprykerShop\Yves\CommentWidget\Operation\CommentOperationInterface
     */
    public function createCommentOperation(): CommentOperationInterface
    {
        return new CommentOperation(
            $this->getCommentThreadAfterOperationPlugins()
        );
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory(): FormFactory
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
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
     * @return \SprykerShop\Yves\CommentWidgetExtension\Dependency\Plugin\CommentThreadAfterOperationStrategyPluginInterface[]
     */
    public function getCommentThreadAfterOperationPlugins(): array
    {
        return $this->getProvidedDependency(CommentWidgetDependencyProvider::PLUGINS_COMMENT_THREAD_AFTER_OPERATION);
    }
}
