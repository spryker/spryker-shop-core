<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Application\Environment as ApplicationEnvironment;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Log\LogConstants;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Kernel\ControllerResolver\YvesFragmentControllerResolver;
use Spryker\Yves\Kernel\Plugin\Pimple;
use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated There are four classes created as replacement for current one.
 * @see \SprykerShop\Yves\ShopApplication\Plugin\Twig\ShopApplicationTwigPlugin
 * @see \SprykerShop\Yves\ShopApplication\Plugin\Application\ShopApplicationApplicationPlugin
 * @see \Spryker\Yves\Store\Plugin\Application\StoreApplicationPlugin
 * @see \Spryker\Yves\Locale\Plugin\Application\LocaleApplicationPlugin
 *
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationFactory getFactory()
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationConfig getConfig()
 */
class ShopApplicationServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    public const LOCALE = 'locale';
    public const STORE = 'store';
    public const REQUEST_URI = 'REQUEST_URI';

    /**
     * @var \Spryker\Shared\Kernel\Communication\Application
     */
    private $application;

    /**
     * @param \Spryker\Shared\Kernel\Communication\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $this->application = $app;

        $this->setPimpleApplication();
        $this->setDebugMode();
        $this->setControllerResolver();
        $this->setLocale();
        $this->setStore();
        $this->setLogLevel();

        $this->addGlobalTemplateVariables($app, [
            'environment' => $this->getConfig()->getTwigEnvironmentName(),
        ]);
    }

    /**
     * @param \Spryker\Shared\Kernel\Communication\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }

    /**
     * @return void
     */
    protected function setPimpleApplication()
    {
        $pimplePlugin = new Pimple();
        $pimplePlugin->setApplication($this->application);
    }

    /**
     * @return void
     */
    protected function setDebugMode()
    {
        $this->application['debug'] = Config::get(ApplicationConstants::ENABLE_APPLICATION_DEBUG, false);
    }

    /**
     * @return void
     */
    protected function setControllerResolver()
    {
        $this->application['resolver'] = $this->application->share(function () {
            return new YvesFragmentControllerResolver($this->application);
        });
    }

    /**
     * @return void
     */
    protected function setLocale()
    {
        $store = Store::getInstance();
        $store->setCurrentLocale(current($store->getLocales()));
        $this->application[self::LOCALE] = $store->getCurrentLocale();

        $requestUri = $this->getRequestUri();

        if ($requestUri) {
            $pathElements = explode('/', trim($requestUri, '/'));
            $identifier = $pathElements[0];
            if ($identifier !== false && array_key_exists($identifier, $store->getLocales())) {
                $store->setCurrentLocale($store->getLocales()[$identifier]);
                $this->application[self::LOCALE] = $store->getCurrentLocale();
                ApplicationEnvironment::initializeLocale($store->getCurrentLocale());
            }
        }
    }

    /**
     * @return void
     */
    protected function setStore()
    {
        $store = Store::getInstance();

        $this->application[self::STORE] = $store->getStoreName();
    }

    /**
     * @return void
     */
    protected function setLogLevel()
    {
        $this->application['monolog.level'] = Config::get(LogConstants::LOG_LEVEL);
    }

    /**
     * @return string
     */
    protected function getRequestUri()
    {
        $requestUri = Request::createFromGlobals()
            ->server->get(self::REQUEST_URI);

        return $requestUri;
    }

    /**
     * @param \Spryker\Shared\Kernel\Communication\Application $app
     * @param array $globalTemplateVariables
     *
     * @return void
     */
    protected function addGlobalTemplateVariables(Application $app, array $globalTemplateVariables)
    {
        $app['twig.global.variables'] = $app->share(
            $app->extend('twig.global.variables', function (array $variables) use ($globalTemplateVariables) {
                return array_merge($variables, $globalTemplateVariables);
            })
        );
    }
}
