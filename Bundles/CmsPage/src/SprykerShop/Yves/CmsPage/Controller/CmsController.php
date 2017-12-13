<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CmsPage\Controller;

use DateTime;
use Generated\Shared\Transfer\LocaleCmsPageDataTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\CmsPage\CmsPageFactory getFactory()
 */
class CmsController extends AbstractController
{
    /**
     * @param array $data
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function pageAction($data, Request $request)
    {
        $localeCmsPageDataTransfer = $this->getFactory()
            ->getCmsStorageClient()
            ->mapCmsPageStorageData($data);

        $edit = (bool)$request->get('edit', false);

        if (!$localeCmsPageDataTransfer->getIsActive()) {
            throw new NotFoundHttpException('The Cms Page is not active.');
        }

        if (!$this->isPageActiveInGivenDate($localeCmsPageDataTransfer, new DateTime())) {
            throw new NotFoundHttpException('The Cms Page is not active in given dates.');
        }

        $loader = $this->getApplication()['twig']->getLoader();
        if (!$loader->exists($localeCmsPageDataTransfer->getTemplatePath())) {
            throw new NotFoundHttpException('The Cms Page template is not found.');
        }

        $placeholders = $this->getFactory()
            ->getCmsTwigRendererPlugin()
            ->render($localeCmsPageDataTransfer->getPlaceholders(), ['cmsContent' => $data]);

        $responseData = [
            'placeholders' => $placeholders,
            'edit' => $edit,
            'pageTitle' => $localeCmsPageDataTransfer->getMetaTitle(),
            'pageDescription' => $localeCmsPageDataTransfer->getMetaDescription(),
            'pageKeywords' => $localeCmsPageDataTransfer->getMetaKeywords(),
        ];

        return $this->view($responseData, [], $localeCmsPageDataTransfer->getTemplatePath());
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleCmsPageDataTransfer $localeCmsPageDataTransfer
     * @param \DateTime $dateToCompare
     *
     * @return bool
     */
    protected function isPageActiveInGivenDate(LocaleCmsPageDataTransfer $localeCmsPageDataTransfer, DateTime $dateToCompare)
    {
        if ($localeCmsPageDataTransfer->getValidFrom() && $localeCmsPageDataTransfer->getValidTo()) {
            $validFrom = new DateTime($localeCmsPageDataTransfer->getValidFrom());
            $validTo = new DateTime($localeCmsPageDataTransfer->getValidTo());

            if ($dateToCompare >= $validFrom && $dateToCompare <= $validTo) {
                return true;
            }

            return false;
        }

        return true;
    }
}
