<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppKernel\Business;

use Generated\Shared\Transfer\AppConfigCriteriaTransfer;
use Generated\Shared\Transfer\AppConfigResponseTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\ConfigurationValidationRequestTransfer;
use Generated\Shared\Transfer\ConfigurationValidationResponseTransfer;

interface AppKernelFacadeInterface
{
    /**
     * Specification:
     * - Converts the `GlueRequestTransfer::getContent()` data from a JSON string into a `AppConfigTransfer`.
     * - Calls `PlatformPluginInterface::validateConfiguration()` and passes the `AppConfigTransfer`.
     * - When `PlatformPluginInterface::validateConfiguration()` throws an exception, the exception is logged.
     * - When `PlatformPluginInterface::validateConfiguration()` throws an exception, a `GlueRequestValidationTransfer` with a failed response is returned.
     * - When `PlatformPluginInterface::validateConfiguration()` is successful, a `GlueRequestValidationTransfer` with HTTP Status Code 200 (OK) is returned.
     * - When `PlatformPluginInterface::validateConfiguration()` is not successful, validation errors from the `AppConfigValidateResponseTransfer` are converted
     *   to error messages and added to the `GlueRequestValidationTransfer`.
     * - When `PlatformPluginInterface::validateConfiguration()` is NOT successful, a `GlueRequestValidationTransfer` with HTTP Status Code 422 (UNPROCESSABLE ENTITY) is returned.
     * - Requires `GlueRequestTransfer::getContent()`.
     *
     * @api
     */
    public function validateConfiguration(
        ConfigurationValidationRequestTransfer $configurationValidationRequestTransfer
    ): ConfigurationValidationResponseTransfer;

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
     */
    public function getConfig(AppConfigCriteriaTransfer $appConfigCriteriaTransfer): AppConfigTransfer;
}
