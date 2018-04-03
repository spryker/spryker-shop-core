<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication;

use Spryker\Yves\Application\ApplicationConfig;
use Spryker\Yves\Kernel\Application;

abstract class YvesBootstrap
{
    /**
     * @var \Spryker\Yves\Kernel\Application
     */
    protected $application;

    /**
     * @var \Spryker\Yves\Application\ApplicationConfig
     */
    protected $config;

    public function __construct()
    {
        $this->application = new Application();
        $this->config = new ApplicationConfig();
    }

    /**
     * @return \Spryker\Yves\Kernel\Application
     */
    public function boot()
    {
        $this->registerServiceProviders();
        $this->registerRouters();
        $this->registerControllerProviders();

        return $this->application;
    }

    /**
     * @return void
     */
    abstract protected function registerServiceProviders();

    /**
     * @return void
     */
    abstract protected function registerRouters();

    /**
     * @return void
     */
    abstract protected function registerControllerProviders();
}
