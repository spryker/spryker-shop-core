<?php

namespace SprykerShop\Yves\FileManager;


use Spryker\Service\FileManager\FileManagerService;
use Spryker\Yves\Kernel\AbstractFactory;

class FileManagerFactory extends AbstractFactory
{

    /**
     * @return FileManagerService
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function getFileManagerService()
    {
        return $this->getProvidedDependency(FileManagerDependencyProvider::FILE_MANAGER_SERVICE);
    }

}