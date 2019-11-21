<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopCmsSlot\Business;

use Generated\Shared\Transfer\CmsSlotContentRequestTransfer;
use Generated\Shared\Transfer\CmsSlotContentResponseTransfer;
use Generated\Shared\Transfer\CmsSlotContextTransfer;
use Generated\Shared\Transfer\CmsSlotParamsTransfer;
use SprykerShop\Yves\ShopCmsSlot\Dependency\Client\ShopCmsSlotToCmsSlotClientInterface;
use SprykerShop\Yves\ShopCmsSlot\Dependency\Client\ShopCmsSlotToCmsSlotStorageClientInterface;
use SprykerShop\Yves\ShopCmsSlot\Exception\MissingCmsSlotContentPluginException;
use SprykerShop\Yves\ShopCmsSlot\Exception\MissingRequiredParameterException;
use SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotContentPluginInterface;

class CmsSlotDataProvider implements CmsSlotDataProviderInterface
{
    /**
     * @var \SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotContentPluginInterface[]
     */
    protected $cmsSlotContentPlugins;

    /**
     * @var \SprykerShop\Yves\ShopCmsSlot\Dependency\Client\ShopCmsSlotToCmsSlotClientInterface
     */
    protected $cmsSlotClient;

    /**
     * @var \SprykerShop\Yves\ShopCmsSlot\Dependency\Client\ShopCmsSlotToCmsSlotStorageClientInterface
     */
    protected $cmsSlotStorageClient;

    /**
     * @param \SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotContentPluginInterface[] $cmsSlotContentPlugins
     * @param \SprykerShop\Yves\ShopCmsSlot\Dependency\Client\ShopCmsSlotToCmsSlotClientInterface $cmsSlotClient
     * @param \SprykerShop\Yves\ShopCmsSlot\Dependency\Client\ShopCmsSlotToCmsSlotStorageClientInterface $cmsSlotStorageClient
     */
    public function __construct(
        array $cmsSlotContentPlugins,
        ShopCmsSlotToCmsSlotClientInterface $cmsSlotClient,
        ShopCmsSlotToCmsSlotStorageClientInterface $cmsSlotStorageClient
    ) {
        $this->cmsSlotContentPlugins = $cmsSlotContentPlugins;
        $this->cmsSlotClient = $cmsSlotClient;
        $this->cmsSlotStorageClient = $cmsSlotStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotContextTransfer $cmsSlotContextTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotContentResponseTransfer
     */
    public function getSlotContent(CmsSlotContextTransfer $cmsSlotContextTransfer): CmsSlotContentResponseTransfer
    {
        $cmsSlotStorageTransfer = $this->cmsSlotStorageClient->getCmsSlotByKey(
            $cmsSlotContextTransfer->getCmsSlotKey()
        );

        if (!$cmsSlotStorageTransfer->getIsActive()) {
            return (new CmsSlotContentResponseTransfer())->setContent('');
        }

        $providedData = $cmsSlotContextTransfer->getProvidedData();
        $autoFilledKeys = $cmsSlotContextTransfer->getAutoFilledKeys();

        $this->assureProvidedHasRequiredKeys($providedData, $cmsSlotContextTransfer->getRequiredKeys());

        if ($autoFilledKeys) {
            $autoFilledData = $this->cmsSlotClient->getCmsSlotExternalDataByKeys($autoFilledKeys)->getValues();
            $providedData = $this->mergeProvidedData($autoFilledData, $providedData);
        }

        $cmsSlotContentRequestTransfer = $this->createCmsSlotContentRequestTransfer($cmsSlotContextTransfer, $providedData);
        $cmsSlotContentPlugin = $this->getContentPlugin($cmsSlotStorageTransfer->getContentProviderType());

        return $cmsSlotContentPlugin->getSlotContent($cmsSlotContentRequestTransfer);
    }

    /**
     * @param string $contentProviderType
     *
     * @throws \SprykerShop\Yves\ShopCmsSlot\Exception\MissingCmsSlotContentPluginException
     *
     * @return \SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotContentPluginInterface
     */
    protected function getContentPlugin(string $contentProviderType): CmsSlotContentPluginInterface
    {
        if (!isset($this->cmsSlotContentPlugins[$contentProviderType])) {
            throw new MissingCmsSlotContentPluginException(
                sprintf(
                    'There is no CMS slot content plugin registered for this content provider type: %s, ' .
                    'you can fix this error by adding it in ShopCmsSlotDependencyProvider',
                    $contentProviderType
                )
            );
        }

        return $this->cmsSlotContentPlugins[$contentProviderType];
    }

    /**
     * @param array $provided
     * @param string[] $requiredKeys
     *
     * @throws \SprykerShop\Yves\ShopCmsSlot\Exception\MissingRequiredParameterException
     *
     * @return void
     */
    protected function assureProvidedHasRequiredKeys(array $provided, array $requiredKeys): void
    {
        if (!$requiredKeys) {
            return;
        }

        foreach ($requiredKeys as $requiredKey) {
            if (!isset($provided[$requiredKey])) {
                throw new MissingRequiredParameterException(
                    sprintf('Unable to find provided data for the key "%s"', $requiredKey)
                );
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotContextTransfer $cmsSlotContextTransfer
     * @param \Generated\Shared\Transfer\CmsSlotExternalDataTransfer[] $providedData
     *
     * @return \Generated\Shared\Transfer\CmsSlotContentRequestTransfer
     */
    protected function createCmsSlotContentRequestTransfer(
        CmsSlotContextTransfer $cmsSlotContextTransfer,
        array $providedData
    ): CmsSlotContentRequestTransfer {
        $cmsSlotContentRequestTransfer = (new CmsSlotContentRequestTransfer())
            ->setCmsSlotKey($cmsSlotContextTransfer->getCmsSlotKey())
            ->setCmsSlotTemplatePath($cmsSlotContextTransfer->getCmsSlotTemplatePath())
            ->setCmsSlotParams(
                (new CmsSlotParamsTransfer())->fromArray($providedData, true)
            );

        return $cmsSlotContentRequestTransfer;
    }

    /**
     * @param string[] $autoFilledData
     * @param \Generated\Shared\Transfer\CmsSlotExternalDataTransfer[] $providedData
     *
     * @return \Generated\Shared\Transfer\CmsSlotExternalDataTransfer[]
     */
    protected function mergeProvidedData(array $autoFilledData, array $providedData): array
    {
        return array_merge($autoFilledData, $providedData);
    }
}
