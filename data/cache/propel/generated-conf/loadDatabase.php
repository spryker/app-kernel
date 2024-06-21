<?php
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->initDatabaseMapFromDumps(array (
  'zed' =>
  array (
    'tablesByName' =>
    array (
      'spy_app_config' => '\\Orm\\Zed\\AppKernel\\Persistence\\Map\\SpyAppConfigTableMap',
      'spy_locale' => '\\Orm\\Zed\\Locale\\Persistence\\Map\\SpyLocaleTableMap',
      'spy_locale_store' => '\\Orm\\Zed\\Locale\\Persistence\\Map\\SpyLocaleStoreTableMap',
      'spy_queue_process' => '\\Orm\\Zed\\Queue\\Persistence\\Map\\SpyQueueProcessTableMap',
      'spy_store' => '\\Orm\\Zed\\Store\\Persistence\\Map\\SpyStoreTableMap',
    ),
    'tablesByPhpName' =>
    array (
      '\\SpyAppConfig' => '\\Orm\\Zed\\AppKernel\\Persistence\\Map\\SpyAppConfigTableMap',
      '\\SpyLocale' => '\\Orm\\Zed\\Locale\\Persistence\\Map\\SpyLocaleTableMap',
      '\\SpyLocaleStore' => '\\Orm\\Zed\\Locale\\Persistence\\Map\\SpyLocaleStoreTableMap',
      '\\SpyQueueProcess' => '\\Orm\\Zed\\Queue\\Persistence\\Map\\SpyQueueProcessTableMap',
      '\\SpyStore' => '\\Orm\\Zed\\Store\\Persistence\\Map\\SpyStoreTableMap',
    ),
  ),
));
