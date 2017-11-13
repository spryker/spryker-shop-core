<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ShopTranslator\Plugin\Provider;

use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractServiceProvider;
use Silex\Application;

/**
 * @method \SprykerShop\Yves\ShopTranslator\ShopTranslatorFactory getFactory()
 * @method \Spryker\Client\Glossary\GlossaryClientInterface getClient()
 */
class TranslationServiceProvider extends AbstractServiceProvider
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['translator'] = $app->share(function ($app) {
            $twigTranslator = $this->getFactory()->createTwigTranslator(
                $app['locale']
            );

            return $twigTranslator;
        });
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }
}
