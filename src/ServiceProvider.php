<?php

namespace DreamFactory\Core\Informix;

use DreamFactory\Core\Components\DbSchemaExtensions;
use DreamFactory\Core\Enums\LicenseLevel;
use DreamFactory\Core\Enums\ServiceTypeGroups;
use DreamFactory\Core\Informix\Database\Connectors\InformixConnector;
use DreamFactory\Core\Informix\Database\InformixConnection;
use DreamFactory\Core\Informix\Database\Schema\InformixSchema;
use DreamFactory\Core\Informix\Models\InformixDbConfig;
use DreamFactory\Core\Informix\Services\Informix;
use DreamFactory\Core\Services\ServiceManager;
use DreamFactory\Core\Services\ServiceType;
use Illuminate\Database\DatabaseManager;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        // Add our database drivers
        $this->app->resolving('db', function (DatabaseManager $db) {
            $db->extend('informix', function ($config) {
                $connector = new InformixConnector();
                $connection = $connector->connect($config);

                return new InformixConnection($connection, $config["database"], $config["prefix"], $config);
            });
        });

        // Add our service types
        $this->app->resolving('df.service', function (ServiceManager $df) {
            $df->addType(
                new ServiceType([
                    'name'                  => 'informix',
                    'label'                 => 'IBM Informix',
                    'description'           => 'Database service supporting IBM Informix SQL connections.',
                    'group'                 => ServiceTypeGroups::DATABASE,
                    'subscription_required' => LicenseLevel::SILVER,
                    'config_handler'        => InformixDbConfig::class,
                    'factory'               => function ($config) {
                        return new Informix($config);
                    },
                ])
            );
        });

        // Add our database extensions
        $this->app->resolving('db.schema', function (DbSchemaExtensions $db) {
            $db->extend('informix', function ($connection) {
                return new InformixSchema($connection);
            });
        });
    }
}
