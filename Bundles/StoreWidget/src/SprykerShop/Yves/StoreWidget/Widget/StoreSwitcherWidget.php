<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\StoreWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\StoreWidget\StoreWidgetFactory getFactory()
 */
class StoreSwitcherWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_CURRENT_STORE_NAME = 'currentStoreName';

    /**
     * @var string
     */
    protected const PARAMETER_STORE_NAMES = 'storeNames';

    /**
     * @var string
     */
    protected const PARAMETER_IS_DYNAMIC_STORE_ENABLED = 'isDynamicStoreEnabled';

    public function __construct()
    {
        $this->addCurrentStoreParameter();
        $this->addStoreNamesParameter();
        $this->addIsDynamicStoreEnabledParameter();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'StoreSwitcher';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@StoreWidget/views/switcher/switcher.twig';
    }

    /**
     * @return void
     */
    protected function addCurrentStoreParameter(): void
    {
        $this->addParameter(
            static::PARAMETER_CURRENT_STORE_NAME,
            $this->getFactory()->getStoreClient()->getCurrentStore()->getNameOrFail(),
        );
    }

    /**
     * @return void
     */
    protected function addStoreNamesParameter(): void
    {
        $this->addParameter(
            static::PARAMETER_STORE_NAMES,
            $this->getFactory()->getStoreStorageClient()->getStoreNames(),
        );
    }

    /**
     * @return void
     */
    protected function addIsDynamicStoreEnabledParameter(): void
    {
        $this->addParameter(
            static::PARAMETER_IS_DYNAMIC_STORE_ENABLED,
            $this->getFactory()->getStoreClient()->isDynamicStoreEnabled(),
        );
    }
}
