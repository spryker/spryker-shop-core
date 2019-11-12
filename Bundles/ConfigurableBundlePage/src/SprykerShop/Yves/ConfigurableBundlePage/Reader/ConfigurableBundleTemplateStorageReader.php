<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageRequestTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageResponseTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundleStorageClientInterface;
use SprykerShop\Yves\ConfigurableBundlePage\Validator\ConfigurableBundleTemplateSlotCombinationValidatorInterface;

class ConfigurableBundleTemplateStorageReader implements ConfigurableBundleTemplateStorageReaderInterface
{
    protected const GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND = 'configurable_bundle_page.template_not_found';
    protected const GLOSSARY_KEY_INVALID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_COMBINATION = 'configurable_bundle_page.invalid_template_slot_combination';

    /**
     * @var \SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundleStorageClientInterface
     */
    protected $configurableBundleStorageClient;

    /**
     * @var \SprykerShop\Yves\ConfigurableBundlePage\Validator\ConfigurableBundleTemplateSlotCombinationValidatorInterface
     */
    protected $configurableBundleTemplateSlotCombinationValidator;

    /**
     * @param \SprykerShop\Yves\ConfigurableBundlePage\Dependency\Client\ConfigurableBundlePageToConfigurableBundleStorageClientInterface $configurableBundleStorageClient
     * @param \SprykerShop\Yves\ConfigurableBundlePage\Validator\ConfigurableBundleTemplateSlotCombinationValidatorInterface $configurableBundleTemplateSlotCombinationValidator
     */
    public function __construct(
        ConfigurableBundlePageToConfigurableBundleStorageClientInterface $configurableBundleStorageClient,
        ConfigurableBundleTemplateSlotCombinationValidatorInterface $configurableBundleTemplateSlotCombinationValidator
    ) {
        $this->configurableBundleStorageClient = $configurableBundleStorageClient;
        $this->configurableBundleTemplateSlotCombinationValidator = $configurableBundleTemplateSlotCombinationValidator;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageRequestTransfer $configurableBundleTemplateStorageRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageResponseTransfer
     */
    public function getConfigurableBundleTemplateStorage(
        ConfigurableBundleTemplateStorageRequestTransfer $configurableBundleTemplateStorageRequestTransfer
    ): ConfigurableBundleTemplateStorageResponseTransfer {
        $configurableBundleTemplateStorageTransfer = $this->configurableBundleStorageClient->findConfigurableBundleTemplateStorage(
            $configurableBundleTemplateStorageRequestTransfer->getIdConfigurableBundleTemplate()
        );

        if (!$configurableBundleTemplateStorageTransfer) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND);
        }

        if (!$configurableBundleTemplateStorageRequestTransfer->getIdConfigurableBundleTemplateSlot()) {
            $configurableBundleTemplateStorageTransfer->setSlots(
                $this->getIndexedConfigurableBundleTemplateStorageTransfers($configurableBundleTemplateStorageTransfer->getSlots())
            );

            return $this->getSuccessfulResponse($configurableBundleTemplateStorageTransfer);
        }

        return $this->validateTemplateSlotCombination(
            $configurableBundleTemplateStorageTransfer,
            $configurableBundleTemplateStorageRequestTransfer->getIdConfigurableBundleTemplateSlot()
        );
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer[] $configurableBundleTemplateSlotStorageTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ConfigurableBundleTemplateSlotStorageTransfer[]
     */
    protected function getIndexedConfigurableBundleTemplateStorageTransfers(ArrayObject $configurableBundleTemplateSlotStorageTransfers): ArrayObject
    {
        $indexedConfigurableBundleTemplateSlotStorageTransfers = new ArrayObject();

        foreach ($configurableBundleTemplateSlotStorageTransfers as $configurableBundleTemplateSlotStorageTransfer) {
            $indexedConfigurableBundleTemplateSlotStorageTransfers->offsetSet(
                $configurableBundleTemplateSlotStorageTransfer->getIdConfigurableBundleTemplateSlot(),
                $configurableBundleTemplateSlotStorageTransfer
            );
        }

        return $indexedConfigurableBundleTemplateSlotStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     * @param int $idConfigurableBundleTemplateSlot
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageResponseTransfer
     */
    protected function validateTemplateSlotCombination(ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer, int $idConfigurableBundleTemplateSlot): ConfigurableBundleTemplateStorageResponseTransfer
    {
        $isTemplateSlotCombinationValid = $this->configurableBundleTemplateSlotCombinationValidator->validateConfigurableBundleTemplateSlotCombination(
            $configurableBundleTemplateStorageTransfer,
            $idConfigurableBundleTemplateSlot
        );

        if (!$isTemplateSlotCombinationValid) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_INVALID_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT_COMBINATION);
        }

        $configurableBundleTemplateStorageTransfer->setSlots(
            $this->getIndexedConfigurableBundleTemplateStorageTransfers($configurableBundleTemplateStorageTransfer->getSlots())
        );

        return $this->getSuccessfulResponse($configurableBundleTemplateStorageTransfer);
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageResponseTransfer
     */
    protected function getErrorResponse(string $message): ConfigurableBundleTemplateStorageResponseTransfer
    {
        $messageTransfer = (new MessageTransfer())->setValue($message);

        return (new ConfigurableBundleTemplateStorageResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageResponseTransfer
     */
    protected function getSuccessfulResponse(ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer): ConfigurableBundleTemplateStorageResponseTransfer
    {
        return (new ConfigurableBundleTemplateStorageResponseTransfer())
            ->setConfigurableBundleTemplateStorage($configurableBundleTemplateStorageTransfer)
            ->setIsSuccessful(true);
    }
}
