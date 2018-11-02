<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Form\Filter;

use Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection;

interface SubFormFilterInterface
{
    /**
     * @param \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection $subFormPlugins
     *
     * @return \Spryker\Yves\StepEngine\Dependency\Plugin\Form\SubFormPluginCollection
     */
    public function filterFormsCollection(
        SubFormPluginCollection $subFormPlugins
    );
}
