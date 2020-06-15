<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesProductBundleWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\SalesProductBundleWidget\Expander\ReturnCreateFormExpander;
use SprykerShop\Yves\SalesProductBundleWidget\Expander\ReturnCreateFormExpanderInterface;
use SprykerShop\Yves\SalesProductBundleWidget\Extractor\ItemExtractor;
use SprykerShop\Yves\SalesProductBundleWidget\Extractor\ItemExtractorInterface;
use SprykerShop\Yves\SalesProductBundleWidget\Form\ReturnProductBundleForm;
use SprykerShop\Yves\SalesProductBundleWidget\Handler\ReturnCreateFormHandler;
use SprykerShop\Yves\SalesProductBundleWidget\Handler\ReturnCreateFormHandlerInterface;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @method \SprykerShop\Yves\SalesProductBundleWidget\SalesProductBundleWidgetConfig getConfig()
 */
class SalesProductBundleWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\SalesProductBundleWidget\Extractor\ItemExtractorInterface
     */
    public function createItemExtractor(): ItemExtractorInterface
    {
        return new ItemExtractor();
    }

    /**
     * @return \SprykerShop\Yves\SalesProductBundleWidget\Expander\ReturnCreateFormExpanderInterface
     */
    public function createReturnCreateFormExpander(): ReturnCreateFormExpanderInterface
    {
        return new ReturnCreateFormExpander();
    }

    /**
     * @return \SprykerShop\Yves\SalesProductBundleWidget\Handler\ReturnCreateFormHandlerInterface
     */
    public function createReturnCreateFormHandler(): ReturnCreateFormHandlerInterface
    {
        return new ReturnCreateFormHandler();
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createReturnProductBundleForm(): FormTypeInterface
    {
        return new ReturnProductBundleForm();
    }
}
