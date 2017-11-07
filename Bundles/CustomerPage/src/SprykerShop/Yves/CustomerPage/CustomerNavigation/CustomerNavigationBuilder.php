<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\CustomerNavigation;

use Symfony\Component\HttpFoundation\Request;

class CustomerNavigationBuilder
{
    /**
     * @var \SprykerShop\Yves\CustomerPage\Dependency\Plugin\CustomerNavigationItemBuilderPluginInterface[]
     */
    protected $customerNavigationItemBuilderPlugins = [];

    /**
     * @var \Generated\Shared\Transfer\CustomerNavigationItemTransfer[]
     */
    protected $navigation;

    /**
     * @param \SprykerShop\Yves\CustomerPage\Dependency\Plugin\CustomerNavigationItemBuilderPluginInterface[] $customerNavigationItemBuilderPlugins
     */
    public function __construct(array $customerNavigationItemBuilderPlugins)
    {
        $this->navigation = $this->buildNavigation($customerNavigationItemBuilderPlugins);
    }

    /**
     * @param $customerNavigationItemBuilderPlugins \SprykerShop\Yves\CustomerPage\Dependency\Plugin\CustomerNavigationItemBuilderPluginInterface[]
     *
     * @return \Generated\Shared\Transfer\CustomerNavigationItemTransfer[]
     */
    protected function buildNavigation(array $customerNavigationItemBuilderPlugins): array
    {
        $navigation = [];
        foreach ($customerNavigationItemBuilderPlugins as $customerNavigationItemBuilderPlugin) {
            $navigation[] += $customerNavigationItemBuilderPlugin->buildCustomerNavigationItems();
        }

        return $navigation;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerNavigationItemTransfer[]
     */
    public function getNavigation(): array
    {
        return $this->navigation;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    public function getActiveItem(Request $request): string
    {
    }
}
