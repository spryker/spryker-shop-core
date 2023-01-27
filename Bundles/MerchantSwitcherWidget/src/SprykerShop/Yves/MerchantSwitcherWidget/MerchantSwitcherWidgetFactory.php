<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget;

use ArrayObject;
use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\MerchantSwitcherWidget\Cookie\SelectedMerchantCookie;
use SprykerShop\Yves\MerchantSwitcherWidget\Cookie\SelectedMerchantCookieInterface;
use SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientInterface;
use SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSwitcherClientInterface;
use SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToQuoteClientInterface;
use SprykerShop\Yves\MerchantSwitcherWidget\Form\MerchantSwitcherSelectorForm;
use SprykerShop\Yves\MerchantSwitcherWidget\MerchantReader\MerchantReader;
use SprykerShop\Yves\MerchantSwitcherWidget\MerchantReader\MerchantReaderInterface;
use SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcher\MerchantSwitcher;
use SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcher\MerchantSwitcherInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method \SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcherWidgetConfig getConfig()
 */
class MerchantSwitcherWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\MerchantSwitcherWidget\MerchantReader\MerchantReaderInterface
     */
    public function createMerchantReader(): MerchantReaderInterface
    {
        return new MerchantReader(
            $this->getMerchantSearchClient(),
            $this->createSelectedMerchantCookie(),
            $this->createMerchantSwitcher(),
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcher\MerchantSwitcherInterface
     */
    public function createMerchantSwitcher(): MerchantSwitcherInterface
    {
        return new MerchantSwitcher(
            $this->getQuoteClient(),
            $this->getMerchantSwitcherClient(),
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantSwitcherWidget\Cookie\SelectedMerchantCookieInterface
     */
    public function createSelectedMerchantCookie(): SelectedMerchantCookieInterface
    {
        var_dump($this->getRequestStack()); die;
        return new SelectedMerchantCookie(
            $this->getCookies(),
            $this->getRequestStack(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientInterface
     */
    public function getMerchantSearchClient(): MerchantSwitcherWidgetToMerchantSearchClientInterface
    {
        return $this->getProvidedDependency(MerchantSwitcherWidgetDependencyProvider::CLIENT_MERCHANT_SEARCH);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(MerchantSwitcherWidgetDependencyProvider::SERVICE_REQUEST_STACK);
    }

    /**
     * @return \ArrayObject<int, \Symfony\Component\HttpFoundation\Cookie>
     */
    public function getCookies(): ArrayObject
    {
        return $this->getProvidedDependency(MerchantSwitcherWidgetDependencyProvider::SERVICE_COOKIES);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    public function getApplication(): Application
    {
        return $this->getProvidedDependency(MerchantSwitcherWidgetDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToQuoteClientInterface
     */
    public function getQuoteClient(): MerchantSwitcherWidgetToQuoteClientInterface
    {
        return $this->getProvidedDependency(MerchantSwitcherWidgetDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSwitcherClientInterface
     */
    public function getMerchantSwitcherClient(): MerchantSwitcherWidgetToMerchantSwitcherClientInterface
    {
        return $this->getProvidedDependency(MerchantSwitcherWidgetDependencyProvider::CLIENT_MERCHANT_SWITCHER);
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory(): FormFactory
    {
        return $this->getProvidedDependency(MerchantSwitcherWidgetDependencyProvider::FORM_FACTORY);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getMerchantSwitcherSelectorForm(): FormInterface
    {
        return $this->getFormFactory()->create(MerchantSwitcherSelectorForm::class);
    }
}
