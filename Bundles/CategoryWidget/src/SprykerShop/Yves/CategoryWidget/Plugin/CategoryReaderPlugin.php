<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CategoryWidget\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @method \SprykerShop\Yves\CategoryWidget\CategoryWidgetFactory getFactory()
 * TODO: define interface and clarify how to provide functionality from bundle to other bundles in yves (like facade or client)
 */
class CategoryReaderPlugin extends AbstractPlugin
{

    /**
     * @param string $categoryPath
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array
     */
    public function findCategoryNodeByPath($categoryPath)
    {
        $categoryPathPrefix = '/' . $this->getFactory()->getStore()->getCurrentLanguage();
        $categoryPath = $categoryPathPrefix . '/' . ltrim($categoryPath, '/');

        try {
            $resource = $this->getRouter()->match($categoryPath);
        } catch (ResourceNotFoundException $exception) {
            throw new NotFoundHttpException(
                sprintf('Category node with path "%s" not found.', $categoryPath),
                $exception
            );
        }

        return $resource['categoryNode'];
    }

    /**
     * @return \Symfony\Cmf\Component\Routing\ChainRouterInterface
     */
    protected function getRouter()
    {
        return $this->getFactory()->getApplication()['routers'];
    }

}
