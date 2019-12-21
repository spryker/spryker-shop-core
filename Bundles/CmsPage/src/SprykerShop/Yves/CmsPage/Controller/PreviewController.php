<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsPage\Controller;

use Generated\Shared\Transfer\FlattenedLocaleCmsPageDataRequestTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use SprykerShop\Yves\CmsPage\Plugin\Provider\PreviewControllerProvider;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\CmsPage\CmsPageFactory getFactory()
 */
class PreviewController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request)
    {
        if (!$this->hasPermission()) {
            $this->addErrorMessage('cms.preview.access_denied');

            return $this->view([], [], '@CmsPage/views/preview-denied/preview-denied.twig');
        }

        $idCmsPage = (int)$request->attributes->get(PreviewControllerProvider::PARAM_PAGE);
        $metaData = $this->getMetaData($idCmsPage);

        $this->assertTemplate($metaData['template']);
        $metaData['placeholders'] = $this->getFactory()
            ->getCmsTwigRendererPlugin()
            ->render($metaData['placeholders'], ['cmsContent' => $metaData]);

        $viewData = $this->executeIndexAction($idCmsPage, $metaData);

        return $this->view($viewData, [], $metaData['template']);
    }

    /**
     * @param string $template
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    protected function assertTemplate($template)
    {
        /** @var \Twig\Loader\ExistsLoaderInterface $loader */
        $loader = $this->getApplication()['twig']->getLoader();
        if (!$loader->exists($template)) {
            throw new NotFoundHttpException('The Cms Page template is not found');
        }
    }

    /**
     * @param int $idCmsPage
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array
     */
    protected function getMetaData($idCmsPage)
    {
        $localeCmsPageDataRequestTransfer = $this->getFactory()
            ->getCmsClient()
            ->getFlattenedLocaleCmsPageData(
                (new FlattenedLocaleCmsPageDataRequestTransfer())
                    ->setIdCmsPage($idCmsPage)
                    ->setLocale((new LocaleTransfer())->setLocaleName($this->getLocale()))
            );

        if (!$localeCmsPageDataRequestTransfer->getFlattenedLocaleCmsPageData()) {
            throw new NotFoundHttpException(sprintf('There is no valid Cms page with this id: %d.', $idCmsPage));
        }

        return $localeCmsPageDataRequestTransfer->getFlattenedLocaleCmsPageData();
    }

    /**
     * @return bool
     */
    protected function hasPermission()
    {
        $customer = $this->getFactory()->getCustomerClient()->getCustomer();

        if ($customer === null || $customer->getFkUser() === null) {
            return false;
        }

        return true;
    }

    /**
     * @param int $idCmsPage
     * @param array $metaData
     *
     * @return array
     */
    protected function executeIndexAction(int $idCmsPage, array $metaData): array
    {
        return [
            'idCmsPage' => $idCmsPage,
            'placeholders' => $metaData['placeholders'],
            'pageTitle' => $metaData['meta_title'],
            'pageDescription' => $metaData['meta_description'],
            'pageKeywords' => $metaData['meta_keywords'],
            'availablePreviewLanguages' => $this->getAvailablePreviewLanguages(
                $this->getCurrentPreviewPageUri($idCmsPage),
                $this->getFactory()->getStore()->getLocales(),
                $this->getLocale()
            ),
        ];
    }

    /**
     * @param int $idCmsPage
     *
     * @return string
     */
    protected function getCurrentPreviewPageUri($idCmsPage)
    {
        return $this->getApplication()->path(
            PreviewControllerProvider::ROUTE_PREVIEW,
            [PreviewControllerProvider::PARAM_PAGE => $idCmsPage]
        );
    }

    /**
     * @param string $currentPageUri
     * @param array $locals
     * @param string $currentLocaleName
     *
     * @return array
     */
    protected function getAvailablePreviewLanguages($currentPageUri, array $locals, $currentLocaleName)
    {
        $availablePreviewLanguages = [];
        foreach ($locals as $locale => $localeName) {
            $availablePreviewLanguages[] = [
                'uri' => $this->replaceLocale($currentPageUri, $locals, $locale),
                'locale' => $locale,
                'isCurrentLocale' => $currentLocaleName === $localeName,
            ];
        }

        return $availablePreviewLanguages;
    }

    /**
     * @param string $uri
     * @param array $locals
     * @param string $targetLocale
     *
     * @return string
     */
    protected function replaceLocale($uri, array $locals, $targetLocale)
    {
        $localMatchRegExp = implode('|', array_keys($locals));

        return preg_replace("#^/($localMatchRegExp)/#", "/$targetLocale/", $uri);
    }
}
