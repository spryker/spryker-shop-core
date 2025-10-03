<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRegistrationRequestPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\MerchantRegistrationRequestPage\Dependency\Client\MerchantRegistrationRequestPageToMerchantRegistrationRequestClientBridge;
use SprykerShop\Yves\MerchantRegistrationRequestPage\Dependency\Client\MerchantRegistrationRequestPageToStoreClientBridge;

class MerchantRegistrationRequestPageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_STORE = 'CLIENT_STORE';

    /**
     * @var string
     */
    public const CLIENT_MERCHANT_REGISTRATION_REQUEST = 'CLIENT_MERCHANT_REGISTRATION_REQUEST';

    /**
     * @var string
     */
    public const FORM_FACTORY = 'FORM_FACTORY';

    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addFormFactory($container);
        $container = $this->addStoreClient($container);
        $container = $this->addMerchantRegistrationRequestClient($container);

        return $container;
    }

    protected function addFormFactory(Container $container): Container
    {
        $container->set(static::FORM_FACTORY, function (Container $container) {
            return $container->getApplicationService('form.factory');
        });

        return $container;
    }

    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORE, function (Container $container) {
            return new MerchantRegistrationRequestPageToStoreClientBridge($container->getLocator()->store()->client());
        });

        return $container;
    }

    protected function addMerchantRegistrationRequestClient(Container $container): Container
    {
        $container->set(static::CLIENT_MERCHANT_REGISTRATION_REQUEST, function (Container $container) {
            return new MerchantRegistrationRequestPageToMerchantRegistrationRequestClientBridge(
                $container->getLocator()->merchantRegistrationRequest()->client(),
            );
        });

        return $container;
    }
}
