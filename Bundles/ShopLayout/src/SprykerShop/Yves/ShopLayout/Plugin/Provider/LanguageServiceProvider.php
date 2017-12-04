<?php
/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ShopLayout\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \SprykerShop\Yves\ShopLayout\ShopLayoutFactory getFactory()
 */
class LanguageServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $this->addGlobalTemplateVariables($app, [
            'availableLanguages' => $this->getLanguages(),
            'currentLanguage' => $this->getCurrentLanguage(),
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
     * @return string[]
     */
    protected function getLanguages()
    {
        $languages = [];
        $locales = $this->getFactory()
            ->getStore()
            ->getLocales();

        foreach ($locales as $locale) {
            $languages[] = substr($locale, 0, strpos($locale, '_'));
        }

        return $languages;
    }

    /**
     * @return string
     */
    protected function getCurrentLanguage(): string
    {
        return $this->getFactory()
            ->getStore()
            ->getCurrentLanguage();
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
