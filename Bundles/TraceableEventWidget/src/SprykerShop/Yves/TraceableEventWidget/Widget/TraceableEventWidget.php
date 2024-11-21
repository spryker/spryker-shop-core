<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\TraceableEventWidget\Widget;

use Generated\Shared\Transfer\SearchHttpConfigCriteriaTransfer;
use Generated\Shared\Transfer\SearchHttpConfigTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\TraceableEventWidget\TraceableEventWidgetConfig getConfig()
 * @method \SprykerShop\Yves\TraceableEventWidget\TraceableEventWidgetFactory getFactory()
 */
class TraceableEventWidget extends AbstractWidget
{
    public function __construct()
    {
        $searchHttpConfigTransfer = $this->getFactory()->createSearchHttpClient()->findSearchConfig(new SearchHttpConfigCriteriaTransfer());
        $searchSettings = $searchHttpConfigTransfer !== null ? $searchHttpConfigTransfer->getSettings() : [];

        $this->addParameter('searchSettings', $searchSettings);
        $this->addParameter('adapterMolecules', $this->resolveAdapterMolecules($searchHttpConfigTransfer));
        $this->addParameter('tenantIdentifier', $this->getConfig()->getTenantIdentifier());
        $this->addParameter('debug', $this->getConfig()->isDebugEnabled());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'TraceableEventWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@TraceableEventWidget/views/traceable-event/traceable-event.twig';
    }

    /**
     * The method for resolving available adapter molecules, that in the future can have more complex logic.
     *
     * @param \Generated\Shared\Transfer\SearchHttpConfigTransfer|null $searchHttpConfigTransfer
     *
     * @return array<string>
     */
    protected function resolveAdapterMolecules(?SearchHttpConfigTransfer $searchHttpConfigTransfer): array
    {
        if ($searchHttpConfigTransfer === null) {
            return [];
        }

        return ['traceable-events-algolia'];
    }
}
