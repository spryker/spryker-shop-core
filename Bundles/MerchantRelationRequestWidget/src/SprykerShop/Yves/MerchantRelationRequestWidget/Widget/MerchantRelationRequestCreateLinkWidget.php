<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\MerchantRelationRequestWidget\MerchantRelationRequestWidgetFactory getFactory()
 * @method \SprykerShop\Yves\MerchantRelationRequestWidget\MerchantRelationRequestWidgetConfig getConfig()
 */
class MerchantRelationRequestCreateLinkWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_IS_VISIBLE = 'isVisible';

    /**
     * @var string
     */
    protected const PARAMETER_MERCHANT_REFERENCE = 'merchantReference';

    /**
     * @param string $merchantReference
     */
    public function __construct(string $merchantReference)
    {
        $this->addIsVisibleParameter($merchantReference);
        $this->addMerchantReferenceParameter($merchantReference);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'MerchantRelationRequestCreateLinkWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MerchantRelationRequestWidget/views/merchant-relation-request-create-link/merchant-relation-request-create-link.twig';
    }

    /**
     * @param string $merchantReference
     *
     * @return void
     */
    protected function addIsVisibleParameter(string $merchantReference): void
    {
        $isVisible = $this->getFactory()
            ->createMerchantRelationRequestChecker()
            ->isMerchantApplicableForRequest($merchantReference);

        $this->addParameter(static::PARAMETER_IS_VISIBLE, $isVisible);
    }

    /**
     * @param string $merchantReference
     *
     * @return void
     */
    protected function addMerchantReferenceParameter(string $merchantReference): void
    {
        $this->addParameter(static::PARAMETER_MERCHANT_REFERENCE, $merchantReference);
    }
}
