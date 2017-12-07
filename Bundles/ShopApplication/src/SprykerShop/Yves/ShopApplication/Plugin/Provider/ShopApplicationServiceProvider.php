<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ShopApplication\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Application\Environment as ApplicationEnvironment;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Config\Environment;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Log\LogConstants;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Kernel\ControllerResolver\YvesFragmentControllerResolver;
use Spryker\Yves\Kernel\Plugin\Pimple;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationFactory getFactory()
 */
class ShopApplicationServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    const LOCALE = 'locale';
    const REQUEST_URI = 'REQUEST_URI';

    /**
     * @var \Silex\Application
     */
    private $application;

    /**
     * @param \Silex\Application $app
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
        $this->setLogLevel();

        $this->addGlobalTemplateVariables($app, [
            'environment' => Environment::getEnvironment(),
        ]);
    }

    /**
     * @param \Silex\Application $app
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
     * @param \Silex\Application $app
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
