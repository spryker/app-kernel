<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Business;

use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Generated\Shared\Transfer\AppConfigResponseTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
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
     * - Reads the App configuration from the DB.
     * - Uses PropelEncryptionBehaviour.
     * - Throws `AppConfigNotFoundException` when App configuration by Tenant Identifier could not be found.
     * - Requires `AppConfigCriteriaTransfer.tenantIdentifier` to be set.
     * - Writes Logger::ERROR level log when App configuration by Tenant Identifier could not be found.
     * - Returns a populated `TransferInterface` when App configuration was found.
     *
     * @api
     *
     * @template T of \Spryker\Shared\Kernel\Transfer\TransferInterface
     *
     * @param \Generated\Shared\Transfer\AppConfigCriteriaTransfer $appConfigCriteriaTransfer
     * @param T $transfer
     *
     * @return T
     */
    public function getConfig(AppConfigCriteriaTransfer $appConfigCriteriaTransfer, TransferInterface $transfer): TransferInterface;

    /**
     * Specification:
     * - Sends a message to the tenant about the changed configuration.
     * - Returns the unchanged AppConfigTransfer (Only used).
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigTransfer
     */
    public function informTenantAboutChangedConfiguration(AppConfigTransfer $appConfigTransfer): AppConfigTransfer;

    /**
     * Specification:
     * - Sends a message to the tenant about the removed configuration.
     * - Returns the unchanged AppDisconnectTransfer (Only used).
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AppDisconnectTransfer $appDisconnectTransfer
     *
     * @return \Generated\Shared\Transfer\AppDisconnectTransfer
     */
    public function informTenantAboutDeletedConfiguration(AppDisconnectTransfer $appDisconnectTransfer): AppDisconnectTransfer;
}
