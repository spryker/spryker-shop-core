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
use SprykerShop\Yves\PersistentCartShareWidget\Glossary\GlossaryHelperInterface;

class PersistentCartShareHelper implements PersistentCartShareHelperInterface
{
    /**
     * @see ResourceSharePage::
     */
    protected const LINK_ROUTE = 'link';
    /**
     * @var \SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToPersistentCartShareClientInterface
     */
    protected $persistentCartShareClient;

    /**
     * @var \SprykerShop\Yves\PersistentCartShareWidget\Glossary\GlossaryHelperInterface
     */
    protected $glossaryHelper;

    /**
     * @var \Spryker\Yves\Kernel\Application
     */
    protected $application;

    /**
     * @param \SprykerShop\Yves\PersistentCartShareWidget\Dependency\Client\PersistentCartShareWidgetToPersistentCartShareClientInterface $persistentCartShareClient
     * @param \SprykerShop\Yves\PersistentCartShareWidget\Glossary\GlossaryHelperInterface $glossaryHelper
     */
    public function __construct(
        PersistentCartShareWidgetToPersistentCartShareClientInterface $persistentCartShareClient,
        GlossaryHelperInterface $glossaryHelper,
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
    public function generateLinks(array $cartShareOptions, int $idQuote, string $permissionOptionGroup): array
    {
        if (empty($cartShareOptions[$permissionOptionGroup])) {
            throw new InvalidPermissionOptionException(sprintf('Permission Option "%s" is not valid.', $permissionOptionGroup));
        }

        $links = [];
        foreach ($cartShareOptions[$permissionOptionGroup] as $shareOption) {
            $cartResourceShare = $this->persistentCartShareClient->generateCartResourceShare($idQuote, $shareOption);
            $links[$shareOption] = $this->buildLink($cartResourceShare->getResourceShare());
        }

        return $links;
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
    public function generateLabels(array $cartShareOptions, int $idQuote, string $permissionOptionGroup): array
    {
        if (empty($cartShareOptions[$permissionOptionGroup])) {
            throw new InvalidPermissionOptionException(sprintf('Permission Option "%s" is not valid.', $permissionOptionGroup));
        }

        $labels = [];
        foreach ($cartShareOptions[$permissionOptionGroup] as $shareOption) {
            $labels[$shareOption] = $this->glossaryHelper->getKeyForPermissionOption($permissionOptionGroup, $shareOption);
        }

        return $labels;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $cartResourceShare
     *
     * @return string
     */
    protected function buildLink(ResourceShareTransfer $cartResourceShare): string
    {
        return 'link/' . $cartResourceShare->getUuid(); //todo replace with application->path()
//        return $this->application->path(static::LINK_ROUTE, [])
    }

    /**
     * @return string[]
     */
    public function generateShareOptionGroups(): array
    {
        $cartShareOptions = $this->persistentCartShareClient->getCartShareOptions();
        $cartShareOptionGroups = array_keys($cartShareOptions);

        $result = [];
        foreach ($cartShareOptionGroups as $cartShareOptionGroup) {
            $result[$cartShareOptionGroup] = $this->glossaryHelper->getKeyForPermissionGroup($cartShareOptionGroup);
        }

        return $result;
    }
}
