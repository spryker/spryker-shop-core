<?php

namespace SprykerShop\Yves\FileManager;


use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class FileManagerDependencyProvider extends AbstractBundleDependencyProvider
{

    const FILE_MANAGER_SERVICE = 'FILE_MANAGER_SERVICE';

    /**
     * @param Container $container
     * @return Container
     */
    public function provideDependencies(Container $container)
    {
        $container = parent::provideDependencies($container);
        $container = $this->addFileManagerService($container);

        return $container;
    }

    /**
     * @param Container $container
     * @return Container
     */
    protected function addFileManagerService(Container $container)
    {
        $container[static::FILE_MANAGER_SERVICE] = function (Container $container) {
            return $container->getLocator()->fileManager()->service(); // todo bridge
        };

        return $container;
    }


}