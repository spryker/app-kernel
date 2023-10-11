<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Business;

use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Generated\Shared\Transfer\AppConfigResponseTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\AppDisconnectResponseTransfer;
use Generated\Shared\Transfer\AppDisconnectTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface AppKernelFacadeInterface
{
    /**
     * Specification:
     * - Saves the App configuration in the DB.
     * - Requires `AppConfigTransfer.tenantIdentifier` to be set.
     * - Requires `AppConfigTransfer.config` to be set.
     * - Requires `AppConfigTransfer.clientId` to be set.
     * - Requires `AppConfigTransfer.clientSecret` to be set.
     * - Requires `AppConfigTransfer.isActive` to be set.
     * - Executes `ConfigurationBeforeSavePluginInterface::beforeSave()` method before the configuration is written to the database.
     * - Executes `ConfigurationAfterSavePluginInterface::afterSave()` method after the configuration was written to the database.
     * - Writes Logger::ERROR level log when App configuration could not be written to the database.
     * - Returns a successful `AppConfigResponseTransfer` when configuration was written to the database.
     * - Returns a failed `AppConfigResponseTransfer` when configuration could not be written to the database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigResponseTransfer
     */
    public function saveConfig(AppConfigTransfer $appConfigTransfer): AppConfigResponseTransfer;

    /**
     * Specification:
     * - Deletes the App configuration from the DB.
     * - Requires `AppDisconnectTransfer.tenantIdentifier` to be set.
     * - Executes `ConfigurationBeforeDeletePluginInterface::beforeDelete()` method before the configuration is deleted from the database.
     * - Executes `ConfigurationAfterDeletePluginInterface::afterDelete()` method after the configuration was deleted from the database.
     * - Returns a successful `AppDisconnectResponseTransfer` when configuration was deleted from the database.
     * - Returns a failed `AppDisconnectResponseTransfer` when configuration could not be deleted from the database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AppDisconnectTransfer $appDisconnectTransfer
     *
     * @return \Generated\Shared\Transfer\AppDisconnectResponseTransfer
     */
    public function deleteConfig(AppDisconnectTransfer $appDisconnectTransfer): AppDisconnectResponseTransfer;

    /**
     * Specification:
     * - Reads the App configuration from the DB.
     * - Uses PropelEncryptionBehaviour.
     * - Throws `AppConfigNotFoundException` when App configuration by Tenant Identifier could not be found.
     * - Requires `AppConfigCriteriaTransfer.tenantIdentifier` to be set.
     * - Writes Logger::ERROR level log when App configuration by Tenant Identifier could not be found.
     * - Returns a populated `TransferInterface` when App configuration was found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AppConfigCriteriaTransfer $appConfigCriteriaTransfer
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getConfig(AppConfigCriteriaTransfer $appConfigCriteriaTransfer, TransferInterface $transfer): TransferInterface;
}
