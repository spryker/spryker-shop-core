<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsPage\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @deprecated Use `SprykerShop\Yves\CmsPage\Plugin\Twig\CmsTwigPlugin` instead.
 *
 * @method \SprykerShop\Yves\CmsPage\CmsPageFactory getFactory()
 */
class CmsTwigFunctionServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    public const CMS_PREFIX_KEY = 'generated.cms';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['twig'] = $app->share(
            $app->extend('twig', function (Environment $twig) use ($app) {
                return $this->registerCmsTwigFunction($twig, $app);
            })
        );
    }

    /**
     * @param \Twig\Environment $twig
     * @param \Silex\Application $application
     *
     * @return \Twig\Environment
     */
    protected function registerCmsTwigFunction(Environment $twig, Application $application)
    {
        $twig->addFunction(
            'spyCms',
            new TwigFunction('spyCms', function (array $context, $identifier) use ($application) {
                $placeholders = $context['_view']['placeholders'];

                $translation = '';
                if (isset($placeholders[$identifier])) {
                    $translation = $placeholders[$identifier];
                }

                if ($this->isGlossaryKey($translation)) {
                    $translator = $this->getTranslator($application);
                    $translation = $translator->trans($translation);
                }

                if ($this->isGlossaryKey($translation)) {
                    $translation = '';
                }

                return $translation;
            }, ['needs_context' => true])
        );

        return $twig;
    }

    /**
     * @param string $translation
     *
     * @return bool
     */
    protected function isGlossaryKey($translation)
    {
        return strpos($translation, static::CMS_PREFIX_KEY) === 0;
    }

    /**
     * @param \Silex\Application $application
     *
     * @return \Symfony\Component\Translation\TranslatorInterface
     */
    protected function getTranslator(Application $application)
    {
        return $application['translator'];
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
