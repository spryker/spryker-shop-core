<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CmsPage\Controller;

use DateTime;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\CmsPage\CmsPageFactory getFactory()
 */
class CmsController extends AbstractController
{

    /**
     * @param array $meta
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function pageAction($meta, Request $request)
    {
        $edit = (bool)$request->get('edit', false);

        if (!$meta['is_active']) {
            throw new NotFoundHttpException('The Cms Page is not active');
        }

        if (!$this->isPageActiveInGivenDate($meta, new DateTime())) {
            throw new NotFoundHttpException('The Cms Page is not active in given dates');
        }

        $loader = $this->getApplication()['twig']->getLoader();
        if (!$loader->exists($meta['template'])) {
            throw new NotFoundHttpException('The Cms Page template is not found');
        }

        $meta['placeholders'] = $this->getFactory()
            ->getCmsTwigRendererPlugin()
            ->render($meta['placeholders'], ['cmsContent' => $meta]);

        $data = [
            'placeholders' => $meta['placeholders'],
            'edit' => $edit,
            'pageTitle' => $meta['meta_title'],
            'pageDescription' => $meta['meta_description'],
            'pageKeywords' => $meta['meta_keywords'],
        ];

        return $this->view($data, [], $meta['template']);
    }

    /**
     * @param array $meta
     * @param \DateTime $dateToCompare
     *
     * @return bool
     */
    protected function isPageActiveInGivenDate(array $meta, DateTime $dateToCompare)
    {
        if (isset($meta['valid_from']) && isset($meta['valid_to'])) {

            $validFrom = new DateTime($meta['valid_from']);
            $validTo = new DateTime($meta['valid_to']);

            if ($dateToCompare >= $validFrom && $dateToCompare <= $validTo) {
                return true;
            }

            return false;
        }

        return true;
    }

}
