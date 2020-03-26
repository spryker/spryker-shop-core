<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function pageAction($data, Request $request)
    {
        $localeCmsPageDataTransfer = $this->getFactory()
            ->getCmsStorageClient()
            ->mapCmsPageStorageData($data);

        $viewData = $this->executePageAction($data, $localeCmsPageDataTransfer, $request);

        return $this->view($viewData, [], $localeCmsPageDataTransfer->getTemplatePath());
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleCmsPageDataTransfer $localeCmsPageDataTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array
     */
    protected function executePageAction($data, LocaleCmsPageDataTransfer $localeCmsPageDataTransfer, Request $request): array
    {
        $edit = (bool)$request->get('edit', false);

        if (!$localeCmsPageDataTransfer->getIsActive()) {
            throw new NotFoundHttpException('The Cms Page is not active.');
        }

        if (!$this->isPageActiveInGivenDate($localeCmsPageDataTransfer, new DateTime())) {
            throw new NotFoundHttpException('The Cms Page is not active in given dates.');
        }

        /** @var \Twig\Loader\ExistsLoaderInterface $loader */
        $loader = $this->getApplication()['twig']->getLoader();
        if (!$loader->exists($localeCmsPageDataTransfer->getTemplatePath())) {
            throw new NotFoundHttpException('The Cms Page template is not found.');
        }

        $placeholders = $this->getFactory()
            ->getCmsTwigRendererPlugin()
            ->render($localeCmsPageDataTransfer->getPlaceholders(), ['cmsContent' => $data]);

        return [
            'idCmsPage' => $localeCmsPageDataTransfer->getIdCmsPage(),
            'placeholders' => $placeholders,
            'edit' => $edit,
            'pageTitle' => $localeCmsPageDataTransfer->getMetaTitle(),
            'pageDescription' => $localeCmsPageDataTransfer->getMetaDescription(),
            'pageKeywords' => $localeCmsPageDataTransfer->getMetaKeywords(),
        ];
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
