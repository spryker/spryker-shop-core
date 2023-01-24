<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopTranslator\Model;

use InvalidArgumentException;
use SprykerShop\Yves\ShopTranslator\Dependency\Client\ShopTranslatorToGlossaryStorageClientInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @deprecated Use `spryker/translator` instead.
 */
class TwigTranslator implements TranslatorInterface
{
    /**
     * @var \SprykerShop\Yves\ShopTranslator\Dependency\Client\ShopTranslatorToGlossaryStorageClientInterface
     */
    protected $client;

    /**
     * @var string
     */
    protected $localeName;

    /**
     * @param \SprykerShop\Yves\ShopTranslator\Dependency\Client\ShopTranslatorToGlossaryStorageClientInterface $client
     * @param string|null $localeName
     */
    public function __construct(ShopTranslatorToGlossaryStorageClientInterface $client, $localeName = null)
    {
        $this->client = $client;
        $this->localeName = $localeName;
    }

    /**
     * Translates the given message.
     *
     * @api
     *
     * @param string $id The message identifier (may also be an object that can be cast to string)
     * @param array<string, mixed> $parameters An array of parameters for the message
     * @param string|null $domain The domain for the message or null to use the default
     * @param string|null $locale The locale or null to use the default
     *
     * @return string The translated string
     */
    public function trans(string $id, array $parameters = [], ?string $domain = null, ?string $locale = null): string
    {
        if ($locale === null) {
            $locale = $this->localeName;
        }

        return $this->client->translate($id, $locale, $parameters);
    }

    /**
     * Translates the given choice message by choosing a translation according to a number.
     *
     * @api
     *
     * @param string $identifier The message id (may also be an object that can be cast to string)
     * @param int $number The number to use to find the indice of the message
     * @param array<string, mixed> $parameters An array of parameters for the message
     * @param string|null $domain The domain for the message or null to use the default
     * @param string|null $locale The locale or null to use the default
     *
     * @throws \InvalidArgumentException If the locale contains invalid characters
     *
     * @return string The translated string
     */
    public function transChoice($identifier, $number, array $parameters = [], $domain = null, $locale = null)
    {
        if ($locale === null) {
            $locale = $this->localeName;
        }

        $ids = explode('|', $identifier);

        if ($number === 1) {
            return $this->client->translate($ids[0], $locale, $parameters);
        }

        if (!isset($ids[1])) {
            throw new InvalidArgumentException(sprintf('The message "%s" cannot be pluralized, because it is missing a plural (e.g. "There is one apple|There are %%count%% apples").', $identifier));
        }

        return $this->client->translate($ids[1], $locale, $parameters);
    }

    /**
     * @param string $localeName
     *
     * @return $this
     */
    public function setLocale($localeName)
    {
        $this->localeName = $localeName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->localeName;
    }
}
