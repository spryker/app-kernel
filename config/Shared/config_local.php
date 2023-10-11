<?php

/**
 * This configuration is used for TESTING only and will never be used in production!
 */

use Spryker\Shared\Application\Log\Config\SprykerLoggerConfig;
use Spryker\Shared\Log\LogConstants;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\PropelOrm\Business\Builder\ExtensionObjectBuilder;
use Spryker\Zed\PropelOrm\Business\Builder\ExtensionQueryBuilder;
use Spryker\Zed\PropelOrm\Business\Builder\ObjectBuilder;
use Spryker\Zed\PropelOrm\Business\Builder\QueryBuilder;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\ErrorHandler\ErrorHandlerConstants;

$connections = [
    'mysql' => [
        'adapter' => 'sqlite',
        'dsn' => 'sqlite:tests/_data/app_kernel_db',
        'user' => '',
        'password' => '',
        'settings' => [],
    ],
];

$config[PropelConstants::PROPEL] = [
    'database' => [
        'connections' => [],
    ],
    'runtime' => [
        'defaultConnection' => 'default',
        'connections' => ['default', 'zed'],
    ],
    'generator' => [
        'defaultConnection' => 'default',
        'connections' => ['default', 'zed'],
        'objectModel' => [
            'defaultKeyType' => 'fieldName',
            'builders' => [
                // If you need full entity logging on Create/Update/Delete, then switch to
                // Spryker\Zed\PropelOrm\Business\Builder\ObjectBuilderWithLogger instead.
                'object' => ObjectBuilder::class,
                'objectstub' => ExtensionObjectBuilder::class,
                'query' => QueryBuilder::class,
                'querystub' => ExtensionQueryBuilder::class,
            ],
        ],
    ],
    'paths' => [
        'phpDir' => APPLICATION_ROOT_DIR,
        'sqlDir' => APPLICATION_SOURCE_DIR . '/Orm/Propel/Sql/',
        'migrationDir' => APPLICATION_SOURCE_DIR . '/Orm/Propel/Migration_SQLite/',
        'schemaDir' => APPLICATION_SOURCE_DIR . '/Orm/Propel/Schema/',
    ],
];

$config[PropelConstants::ZED_DB_ENGINE] = 'mysql';
$config[PropelConstants::ZED_DB_HOST] = 'localhost';
$config[PropelConstants::ZED_DB_PORT] = 1234;
$config[PropelConstants::ZED_DB_USERNAME] = 'catface';
$config[PropelConstants::ZED_DB_PASSWORD] = 'catface';

$config[PropelConstants::PROPEL]['database']['connections']['default'] = $connections['mysql'];
$config[PropelConstants::PROPEL]['database']['connections']['zed'] = $connections['mysql'];

$config[KernelConstants::PROJECT_NAMESPACE] = 'Spryker';
$config[KernelConstants::PROJECT_NAMESPACES] = ['Spryker'];
$config[KernelConstants::CORE_NAMESPACES] = ['Spryker'];
$config[KernelConstants::ENABLE_CONTAINER_OVERRIDING] = true;
$config[ErrorHandlerConstants::ERROR_LEVEL] = E_ALL &~E_DEPRECATED;
$config[LogConstants::LOGGER_CONFIG] = SprykerLoggerConfig::class;
$config[LogConstants::LOG_FILE_PATH] = sys_get_temp_dir() . '/logs';


