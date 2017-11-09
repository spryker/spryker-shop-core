<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ShopApplication;

use Spryker\Service\UtilDateTime\Model\DateTimeFormatterTwigExtension;
use Spryker\Yves\Application\ApplicationFactory as SprykerApplicationFactory;
use Spryker\Yves\Application\Plugin\Provider\ExceptionService\SubRequestExceptionHandler;
use Symfony\Component\HttpFoundation\Response;

class ShopApplicationFactory extends SprykerApplicationFactory
{
    /**
     * @return \Spryker\Yves\Application\Plugin\Provider\ExceptionService\ExceptionHandlerInterface[]
     */
    public function createExceptionHandlers()
    {
        $defaultExceptionHandlers = parent::createExceptionHandlers();

        $exceptionHandlers = [
            Response::HTTP_NOT_FOUND => $this->createSubRequestExceptionHandler(),
        ];

        $exceptionHandlers = ($exceptionHandlers + $defaultExceptionHandlers);

        return $exceptionHandlers;
    }

    /**
     * @return \Spryker\Yves\Application\Plugin\Provider\ExceptionService\SubRequestExceptionHandler
     */
    protected function createSubRequestExceptionHandler()
    {
        $application = $this->getApplication();

        return new SubRequestExceptionHandler($application);
    }

    /**
     * @return \Spryker\Yves\Kernel\Application
     */
    protected function getApplication()
    {
        return $this->getProvidedDependency(ShopApplicationDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \Twig_Extension
     */
    public function getTwigYvesExtension()
    {
        return $this->getProvidedDependency(ShopApplicationDependencyProvider::PLUGIN_TWIG);
    }

    /**
     * @return \Spryker\Service\UtilDateTime\Model\DateTimeFormatterTwigExtension
     */
    public function createDateFormatterTwigExtension()
    {
        return new DateTimeFormatterTwigExtension($this->getUtilDateTimeService());
    }

    /**
     * @return \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    protected function getUtilDateTimeService()
    {
        return $this->getProvidedDependency(ShopApplicationDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }
}
