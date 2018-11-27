<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Widget;

use Generated\Shared\Transfer\ProductReviewSearchRequestTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductReviewWidget\ProductReviewWidgetFactory getFactory()
 */
class ProductReviewListWidget extends AbstractWidget
{
    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductReviewListWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductReviewWidget/views/review-widget-list/review-widget-list.twig';
    }

    /**
     * @param int $idProductAbstract
     */
    public function __construct(int $idProductAbstract)
    {
        $parentRequest = $this->getCurrentRequest();
        $productReviews = $this->findProductReviews($idProductAbstract, $parentRequest);

        $this->addParameter('hasCustomer', $this->hasCustomer());
        $this->addParameter('productReviews', $productReviews['productReviews']);
        $this->addParameter('pagination', $productReviews['pagination']);
        $this->addParameter('summary', $this->getFactory()->createProductReviewSummaryCalculator()->execute($productReviews['ratingAggregation']));
        $this->addParameter('maximumRating', $this->getFactory()->getProductReviewClient()->getMaximumRating());
    }

    /**
     * @return bool
     */
    protected function hasCustomer(): bool
    {
        $customer = $this->getFactory()->getCustomerClient()->getCustomer();

        return $customer !== null;
    }

    /**
     * @param int $idProductAbstract
     * @param \Symfony\Component\HttpFoundation\Request $parentRequest
     *
     * @return array
     */
    protected function findProductReviews(int $idProductAbstract, Request $parentRequest): array
    {
        $productReviewSearchRequestTransfer = new ProductReviewSearchRequestTransfer();
        $productReviewSearchRequestTransfer->setIdProductAbstract($idProductAbstract);
        $productReviewSearchRequestTransfer->setRequestParams($parentRequest->query->all());

        return $this->getFactory()
            ->getProductReviewClient()
            ->findProductReviewsInSearch($productReviewSearchRequestTransfer);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getCurrentRequest(): Request
    {
        /**
         * @var \Symfony\Component\HttpFoundation\RequestStack $requestStack
         */
        $requestStack = $this->getApplication()['request_stack'];

        return $requestStack->getCurrentRequest();
    }
}
