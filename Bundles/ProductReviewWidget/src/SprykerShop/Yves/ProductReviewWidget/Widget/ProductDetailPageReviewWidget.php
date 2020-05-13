<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Widget;

use Generated\Shared\Transfer\ProductReviewSearchRequestTransfer;
use Generated\Shared\Transfer\ProductReviewStorageTransfer;
use Generated\Shared\Transfer\RatingAggregationTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductReviewWidget\ProductReviewWidgetFactory getFactory()
 */
class ProductDetailPageReviewWidget extends AbstractWidget
{
    /**
     * @param int $idProductAbstract
     */
    public function __construct(int $idProductAbstract)
    {
        $form = $this->getProductReviewForm($idProductAbstract);
        $request = $this->getApplication()['request'];
        $productReviews = $this->findProductReviews($idProductAbstract, $request);

        $ratingAggregationTransfer = (new RatingAggregationTransfer());
        $ratingAggregationTransfer->setRatingAggregation($productReviews['ratingAggregation']);

        $this->addParameter('idProductAbstract', $idProductAbstract)
            ->addParameter('productReviewStorageTransfer', $this->findProductAbstractReview($idProductAbstract))
            ->addParameter('maximumRating', $this->getMaximumRating())
            ->addParameter('form', $form->createView())
            ->addParameter('hideForm', !$form->isSubmitted())
            ->addParameter('hasCustomer', $this->hasCustomer())
            ->addParameter('productReviews', $productReviews['productReviews'])
            ->addParameter('pagination', $productReviews['pagination'])
            ->addParameter(
                'summary',
                $this->getFactory()
                    ->getProductReviewClient()
                    ->calculateProductReviewSummary($ratingAggregationTransfer)
            )
            ->addParameter('maximumRating', $this->getFactory()->getProductReviewClient()->getMaximumRating());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductDetailPageReviewWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductReviewWidget/views/pdp-review/pdp-review.twig';
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductReviewStorageTransfer|null
     */
    protected function findProductAbstractReview($idProductAbstract): ?ProductReviewStorageTransfer
    {
        return $this->getFactory()
            ->getProductReviewStorageClient()
            ->findProductAbstractReview($idProductAbstract);
    }

    /**
     * @return int
     */
    protected function getMaximumRating(): int
    {
        return $this->getFactory()
            ->getProductReviewClient()
            ->getMaximumRating();
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getProductReviewForm(int $idProductAbstract): FormInterface
    {
        $request = $this->getApplication()['request'];

        return $this->getFactory()
            ->createProductReviewForm($idProductAbstract)
            ->handleRequest($request);
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
}
