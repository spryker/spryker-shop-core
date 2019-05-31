<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CommentWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CommentWidget\Dependency\Client\CommentWidgetToCommentClientInterface;
use SprykerShop\Yves\CommentWidget\Dependency\Client\CommentWidgetToCustomerClientInterface;

/**
 * @method \SprykerShop\Yves\CommentWidget\CommentWidgetConfig getConfig()
 */
class CommentWidgetFactory extends AbstractFactory
{
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
}
