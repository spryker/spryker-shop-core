<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartShareWidget\PersistentCartShare;

use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Yves\Kernel\Application;
use SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToCustomerClientInterface;
use SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToPersistentCartShareClientInterface;
use SprykerShop\Yves\PersistentCartShareWidget\Exceptions\InvalidShareOptionGroupException;
use SprykerShop\Yves\PersistentCartShareWidget\ResourceShare\ResourceShareRequestBuilder;

class PersistentCartShareLinkGenerator implements PersistentCartShareLinkGeneratorInterface
{
    /**
     * @uses \SprykerShop\Yves\ResourceSharePage\Plugin\Provider\ResourceSharePageControllerProvider::ROUTE_RESOURCE_SHARE_LINK
     */
    protected const LINK_ROUTE = 'resource-share/link';
    /**
     * @uses \SprykerShop\Yves\ResourceSharePage\Plugin\Provider\ResourceSharePageControllerProvider::PARAM_RESOURCE_SHARE_UUID
     */
    protected const PARAM_RESOURCE_SHARE_UUID = 'resourceShareUuid';

    /**
     * @var \SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToPersistentCartShareClientInterface
     */
    protected $persistentCartShareClient;

    /**
     * @var \Spryker\Yves\Kernel\Application
     */
    protected $application;

    /**
     * @var \SprykerShop\Yves\PersistentCartShareWidget\ResourceShare\ResourceShareRequestBuilder
     */
    protected $resourceShareRequestBuilder;

    /**
     * @var \SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToPersistentCartShareClientInterface $persistentCartShareClient
     * @param \Spryker\Yves\Kernel\Application $application
     * @param \SprykerShop\Yves\PersistentCartShareWidget\ResourceShare\ResourceShareRequestBuilder $resourceShareRequestBuilder
     * @param \SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToCustomerClientInterface $customerClient
     */
    public function __construct(
        PersistentCartShareWidgetToPersistentCartShareClientInterface $persistentCartShareClient,
        Application $application,
        ResourceShareRequestBuilder $resourceShareRequestBuilder,
        PersistentCartShareWidgetToCustomerClientInterface $customerClient
    ) {
        $this->persistentCartShareClient = $persistentCartShareClient;
        $this->application = $application;
        $this->resourceShareRequestBuilder = $resourceShareRequestBuilder;
        $this->customerClient = $customerClient;
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
            $cartResourceShare = $this->persistentCartShareClient->generateCartResourceShare(
                $this->resourceShareRequestBuilder->buildResourceShareRequest($idQuote, $shareOption)
            );
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
            $resourceShareLinkLabels[$shareOption] = $this->getShareOptionKey($shareOptionGroup, $shareOption);
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
     * @return array
     */
    public function generateShareOptionGroups(): array
    {
        $shareOptions = $this->persistentCartShareClient->getCartShareOptions(
            $this->customerClient->getCustomer()
        );
        $shareOptionGroupNames = array_keys($shareOptions);

        $shareOptionGroups = [];
        foreach ($shareOptionGroupNames as $shareOptionGroupName) {
            $shareOptionGroups[$shareOptionGroupName] = $this->getShareOptionGroupKey($shareOptionGroupName);
        }

        return $shareOptionGroups;
    }

    /**
     * @param string $shareOptionGroupName
     *
     * @return string
     */
    protected function getShareOptionGroupKey(string $shareOptionGroupName): string
    {
        return 'persistent_cart_share.' . $shareOptionGroupName . '_users';
    }

    /**
     * @param string $shareOptionGroup
     * @param string $shareOption
     *
     * @return string
     */
    protected function getShareOptionKey(string $shareOptionGroup, string $shareOption): string
    {
        return 'persistent_cart_share.share_options.' . $shareOptionGroup . '.' . $shareOption;
    }
}
