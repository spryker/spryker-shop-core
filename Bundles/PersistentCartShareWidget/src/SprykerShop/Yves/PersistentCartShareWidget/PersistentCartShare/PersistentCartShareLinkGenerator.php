<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartShareWidget\PersistentCartShare;

use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Yves\Kernel\Application;
use SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToPersistentCartShareClientInterface;
use SprykerShop\Yves\PersistentCartShareWidget\Exceptions\InvalidPermissionOptionException;
use SprykerShop\Yves\PersistentCartShareWidget\Glossary\GlossaryKeyGeneratorInterface;

class PersistentCartShareLinkGenerator implements PersistentCartShareLinkGeneratorInterface
{
    /**
     * @see \SprykerShop\Yves\ResourceSharePage\Plugin\Provider\ResourceSharePageControllerProvider::ROUTE_RESOURCE_SHARE_LINK
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
    protected $glossaryHelper;

    /**
     * @var \Spryker\Yves\Kernel\Application
     */
    protected $application;

    /**
     * @param \SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToPersistentCartShareClientInterface $persistentCartShareClient
     * @param \SprykerShop\Yves\PersistentCartShareWidget\Glossary\GlossaryKeyGeneratorInterface $glossaryHelper
     */
    public function __construct(
        PersistentCartShareWidgetToPersistentCartShareClientInterface $persistentCartShareClient,
        GlossaryKeyGeneratorInterface $glossaryHelper,
        Application $application
    ) {
        $this->persistentCartShareClient = $persistentCartShareClient;
        $this->glossaryHelper = $glossaryHelper;
        $this->application = $application;
    }

    /**
     * @param array $cartShareOptions
     * @param int $idQuote
     * @param string $permissionOptionGroup
     *
     * @throws \SprykerShop\Yves\PersistentCartShareWidget\Exceptions\InvalidPermissionOptionException
     *
     * @return string[]
     */
    public function generateCartShareLinks(array $cartShareOptions, int $idQuote, string $permissionOptionGroup): array
    {
        if (empty($cartShareOptions[$permissionOptionGroup])) {
            throw new InvalidPermissionOptionException(sprintf('Permission Option "%s" is not valid.', $permissionOptionGroup));
        }

        $resourceShareLinks = [];
        foreach ($cartShareOptions[$permissionOptionGroup] as $shareOption) {
            $cartResourceShare = $this->persistentCartShareClient->generateCartResourceShare($idQuote, $shareOption);
            $resourceShareLinks[$shareOption] = $this->buildResourceShareLink($cartResourceShare->getResourceShare());
        }

        return $resourceShareLinks;
    }

    /**
     * @param array $cartShareOptions
     * @param int $idQuote
     * @param string $permissionOptionGroup
     *
     * @throws \SprykerShop\Yves\PersistentCartShareWidget\Exceptions\InvalidPermissionOptionException
     *
     * @return string[]
     */
    public function generateCartShareLinkLabels(array $cartShareOptions, int $idQuote, string $permissionOptionGroup): array
    {
        if (empty($cartShareOptions[$permissionOptionGroup])) {
            throw new InvalidPermissionOptionException(sprintf('Permission Option "%s" is not valid.', $permissionOptionGroup));
        }

        $resourceShareLinkLabels = [];
        foreach ($cartShareOptions[$permissionOptionGroup] as $shareOption) {
            $resourceShareLinkLabels[$shareOption] = $this->glossaryHelper->getKeyForPermissionOption($permissionOptionGroup, $shareOption);
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
    public function generateCartShareOptionGroups(): array
    {
        $cartShareOptions = $this->persistentCartShareClient->getCartShareOptions();
        $optionGroups = array_keys($cartShareOptions);

        $cartShareOptionGroups = [];
        foreach ($optionGroups as $cartShareOptionGroup) {
            $cartShareOptionGroups[$cartShareOptionGroup] = $this->glossaryHelper->getKeyForPermissionGroup($cartShareOptionGroup);
        }

        return $cartShareOptionGroups;
    }
}
