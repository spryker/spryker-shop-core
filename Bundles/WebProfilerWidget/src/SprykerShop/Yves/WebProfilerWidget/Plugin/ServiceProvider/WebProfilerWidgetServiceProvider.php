<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WebProfilerWidget\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @deprecated Use `\SprykerShop\Yves\WebProfiler\Plugin\Application\WebProfilerApplicationPlugin` instead.
 *
 * @method \SprykerShop\Yves\WebProfilerWidget\WebProfilerWidgetFactory getFactory()
 * @method \SprykerShop\Yves\WebProfilerWidget\WebProfilerWidgetConfig getConfig()
 */
class WebProfilerWidgetServiceProvider extends AbstractPlugin implements ServiceProviderInterface, ControllerProviderInterface
{
    /**
     * @var \Silex\ServiceProviderInterface[]
     */
    protected $webProfiler;

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        if ($this->getConfig()->isWebProfilerEnabled()) {
            foreach ($this->getWebProfiler() as $webProfiler) {
                $webProfiler->register($app);
            }
        }
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        if ($this->getConfig()->isWebProfilerEnabled()) {
            foreach ($this->getWebProfiler() as $webProfiler) {
                $webProfiler->boot($app);
            }
        }
    }

    /**
     * @param \Silex\Application $app
     *
     * @return \Silex\ControllerCollection|null
     */
    public function connect(Application $app)
    {
        if ($this->getConfig()->isWebProfilerEnabled()) {
            foreach ($this->getWebProfiler() as $webProfiler) {
                if ($webProfiler instanceof ControllerProviderInterface) {
                    $webProfiler->connect($app);
                }
            }
        }

        return null;
    }

    /**
     * @return \Silex\ServiceProviderInterface[]
     */
    protected function getWebProfiler()
    {
        if (!$this->webProfiler) {
            $this->webProfiler = $this->getFactory()->getWebProfiler();
        }

        return $this->webProfiler;
    }
}
