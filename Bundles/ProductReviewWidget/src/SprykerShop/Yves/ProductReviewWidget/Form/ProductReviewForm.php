<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Form;

use Generated\Shared\Transfer\ProductReviewRequestTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

/**
 * @method \SprykerShop\Yves\ProductReviewWidget\ProductReviewWidgetFactory getFactory()
 * @method \SprykerShop\Yves\ProductReviewWidget\ProductReviewWidgetConfig getConfig()
 */
class ProductReviewForm extends AbstractType
{
    public const FIELD_RATING = ProductReviewRequestTransfer::RATING;
    public const FIELD_SUMMARY = ProductReviewRequestTransfer::SUMMARY;
    public const FIELD_DESCRIPTION = ProductReviewRequestTransfer::DESCRIPTION;
    public const FIELD_NICKNAME = ProductReviewRequestTransfer::NICKNAME;
    public const FIELD_PRODUCT = ProductReviewRequestTransfer::ID_PRODUCT_ABSTRACT;

    public const UNSELECTED_RATING = -1;

    /**
     * @var int
     */
    public const MINIMUM_RATING = 1;

    /**
     * @deprecated Use {@link ProductReviewWidgetConfig::GLOSSARY_KEY_INVALID_RATING_VALIDATION_MESSAGE} instead.
     * @var string
     */
    protected const VALIDATION_RATING_MESSAGE = 'validation.choice';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'productReviewForm';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addRatingField($builder)
            ->addNicknameField($builder)
            ->addSummaryField($builder)
            ->addDescriptionField($builder)
            ->addProductField($builder);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addRatingField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_RATING,
            ChoiceType::class,
            [
                'choices' => array_flip($this->getRatingFieldChoices()),
                'label' => 'product_review.submit.rating',
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'constraints' => [
                    new GreaterThanOrEqual(['value' => static::MINIMUM_RATING]),
                    new LessThanOrEqual(['value' => $this->getFactory()->getProductReviewClient()->getMaximumRating()]),
                ],
                'invalid_message' => $this->getConfig()->getInvalidRatingValidationMessageGlossaryKey(),
            ]
        );

        return $this;
    }

    /**
     * Returns a sequence between predefined minimum and maximum as an array with a leading "unselected" element
     * - keys match values
     *
     * @see ProductReviewForm::UNSELECTED_RATING
     * @see ProductReviewForm::MINIMUM_RATING
     * @see ProductReviewClientInterface::getMaximumRating()
     *
     * Example
     *  [-1 => 'none', 1 => 1, 2 => 2]
     *
     * @return array
     */
    protected function getRatingFieldChoices()
    {
        $choiceKeys = $choiceValues = range(static::MINIMUM_RATING, $this->getFactory()->getProductReviewClient()->getMaximumRating());
        array_unshift($choiceKeys, static::UNSELECTED_RATING);
        array_unshift($choiceValues, 'product_review.submit.rating.none');
        $choices = array_combine($choiceKeys, $choiceValues);

        return $choices;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSummaryField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_SUMMARY,
            TextType::class,
            [
                'label' => 'product_review.submit.summary',
                'required' => true,
                'constraints' => [
                    new Length(['min' => 1]),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDescriptionField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_DESCRIPTION,
            TextareaType::class,
            [
                'label' => 'product_review.submit.description',
                'attr' => [
                    'rows' => 5,
                ],
                'required' => true,
                'constraints' => [
                    new Length(['min' => 1]),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNicknameField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_NICKNAME,
            TextType::class,
            [
                'label' => 'product_review.submit.nickname',
                'required' => true,
                'constraints' => [
                    new Length(['min' => 1, 'max' => 255]),
                ],
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_PRODUCT,
            HiddenType::class,
            [
                'required' => true,
            ]
        );

        return $this;
    }
}
