<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SessionAgentValidation\Expander;

interface SecurityAuthenticationListenerFactoryTypeExpanderInterface
{
    /**
     * @param array<string> $authenticationListenerFactoryTypes
     *
     * @return list<string>
     */
    public function expand(array $authenticationListenerFactoryTypes): array;
}
