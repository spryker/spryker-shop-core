<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopTranslator\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @deprecated Use `\Spryker\Yves\Translator\Plugin\Application\TranslatorApplicationPlugin` instead.
 *
 * @method \SprykerShop\Yves\ShopTranslator\ShopTranslatorFactory getFactory()
 * @method \Spryker\Client\Glossary\GlossaryClientInterface getClient()
 */
class TranslationServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    protected const SERVICE_TRANSLATOR = 'translator';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app[static::SERVICE_TRANSLATOR] = $app->share(function ($app) {
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
