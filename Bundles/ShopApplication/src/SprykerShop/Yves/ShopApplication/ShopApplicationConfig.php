<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class ShopApplicationConfig extends AbstractBundleConfig
{
    /**
     * @return array
     */
    public function getFormThemes()
    {
        return [
            'core_form_div_layout.html.twig',
        ];
    }

    /**
     * @return bool
     */
    public function useViewParametersToRenderTwig(): bool
    {
        return false;
    }
}
