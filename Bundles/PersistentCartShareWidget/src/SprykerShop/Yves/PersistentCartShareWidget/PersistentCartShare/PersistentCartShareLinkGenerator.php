<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartShareWidget\PersistentCartShare;

use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Yves\Kernel\Application;
use SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToPersistentCartShareClientInterface;
use SprykerShop\Yves\PersistentCartShareWidget\Exceptions\InvalidShareOptionGroupException;
use SprykerShop\Yves\PersistentCartShareWidget\Glossary\GlossaryKeyGeneratorInterface;

class PersistentCartShareLinkGenerator implements PersistentCartShareLinkGeneratorInterface
{
    /**
     * @uses \SprykerShop\Yves\ResourceSharePage\Plugin\Provider\ResourceSharePageControllerProvider::ROUTE_RESOURCE_SHARE_LINK
     */
    protected const LINK_ROUTE = 'link';
    protected const PARAM_RESOURCE_SHARE_UUID = 'resourceShareUuid';

    /**
     * @var \SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToPersistentCartShareClientInterface
     */
    protected $persistentCartShareClient;

    /**
     * @var \SprykerShop\Yves\PersistentCartShareWidget\Glossary\GlossaryKeyGeneratorInterface
     */
    protected $glossaryKeyGenerator;

    /**
     * @var \Spryker\Yves\Kernel\Application
     */
    protected $application;

    /**
     * @param \SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToPersistentCartShareClientInterface $persistentCartShareClient
     * @param \SprykerShop\Yves\PersistentCartShareWidget\Glossary\GlossaryKeyGeneratorInterface $glossaryKeyGenerator
     * @param \Spryker\Yves\Kernel\Application $application
     */
    public function __construct(
        PersistentCartShareWidgetToPersistentCartShareClientInterface $persistentCartShareClient,
        GlossaryKeyGeneratorInterface $glossaryKeyGenerator,
        Application $application
    ) {
        $this->persistentCartShareClient = $persistentCartShareClient;
        $this->glossaryKeyGenerator = $glossaryKeyGenerator;
        $this->application = $application;
    }

    /**
     * @param array $shareOptions
     * @param int $idQuote
     * @param string $shareOptionGroup
     *
     * @throws \SprykerShop\Yves\PersistentCartShareWidget\Exceptions\InvalidShareOptionGroupException
     *
     * @return string[]
     */
    public function generateCartShareLinks(array $shareOptions, int $idQuote, string $shareOptionGroup): array
    {
        if (empty($shareOptions[$shareOptionGroup])) {
            throw new InvalidShareOptionGroupException(sprintf('Share Option Group "%s" is not valid.', $shareOptionGroup));
        }

        $resourceShareLinks = [];
        foreach ($shareOptions[$shareOptionGroup] as $shareOption) {
            $cartResourceShare = $this->persistentCartShareClient->generateCartResourceShare($idQuote, $shareOption);
            $resourceShareLinks[$shareOption] = $this->buildResourceShareLink($cartResourceShare->getResourceShare());
        }

        return $resourceShareLinks;
    }

    /**
     * @param array $shareOptions
     * @param int $idQuote
     * @param string $shareOptionGroup
     *
     * @throws \SprykerShop\Yves\PersistentCartShareWidget\Exceptions\InvalidShareOptionGroupException
     *
     * @return string[]
     */
    public function generateCartShareLinkLabels(array $shareOptions, int $idQuote, string $shareOptionGroup): array
    {
        if (empty($shareOptions[$shareOptionGroup])) {
            throw new InvalidShareOptionGroupException(sprintf('Share Option Group "%s" is not valid.', $shareOptionGroup));
        }

        $resourceShareLinkLabels = [];
        foreach ($shareOptions[$shareOptionGroup] as $shareOption) {
            $resourceShareLinkLabels[$shareOption] = $this->glossaryKeyGenerator->getShareOptionKey($shareOptionGroup, $shareOption);
        }

        return $resourceShareLinkLabels;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $cartResourceShare
     *
     * @return string
     */
    protected function buildResourceShareLink(ResourceShareTransfer $cartResourceShare): string
    {
        return $this->application->url(static::LINK_ROUTE, [static::PARAM_RESOURCE_SHARE_UUID => $cartResourceShare->getUuid()]);
    }

    /**
     * @return string[][]
     */
    public function generateShareOptionGroups(): array
    {
        $shareOptions = $this->persistentCartShareClient->getCartShareOptions();
        $shareOptionGroupNames = array_keys($shareOptions);

        $shareOptionGroups = [];
        foreach ($shareOptionGroupNames as $shareOptionGroupName) {
            $shareOptionGroups[$shareOptionGroupName] = $this->glossaryKeyGenerator->getShareOptionGroupKey($shareOptionGroupName);
        }

        return $shareOptionGroups;
    }
}
