<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Controller;

use Generated\Shared\Transfer\ProductReviewSearchRequestTransfer;
use Pyz\Yves\Application\Controller\AbstractController;
use Spryker\Shared\Storage\StorageConstants;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductReviewWidget\ProductReviewWidgetFactory getFactory()
 */
class IndexController extends AbstractController
{
    const STORAGE_CACHE_STRATEGY = StorageConstants::STORAGE_CACHE_STRATEGY_INACTIVE;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request)
    {
        $parentRequest = $this->getParentRequest();
        $idProductAbstract = $request->attributes->get('idProductAbstract');

        $customer = $this->getFactory()->getCustomerClient()->getCustomer();
        $hasCustomer = $customer !== null;

        $productReviewSearchRequestTransfer = new ProductReviewSearchRequestTransfer();
        $productReviewSearchRequestTransfer->setIdProductAbstract($idProductAbstract);
        $productReviewSearchRequestTransfer->setRequestParams($parentRequest->query->all());
        $productReviews = $this->getFactory()
            ->getProductReviewClient()
            ->findProductReviewsInSearch($productReviewSearchRequestTransfer);

        return $this->view([
            'hasCustomer' => $hasCustomer,
            'productReviews' => $productReviews['productReviews'],
            'pagination' => $productReviews['pagination'],
            'summary' => $this->getFactory()->createProductReviewSummaryCalculator()->execute($productReviews['ratingAggregation']),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getParentRequest()
    {
        return $this->getApplication()['request_stack']->getParentRequest();
    }
}
