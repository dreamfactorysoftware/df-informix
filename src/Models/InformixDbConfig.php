<?php
namespace DreamFactory\Core\Informix\Models;

use DreamFactory\Core\SqlDb\Models\SqlDbConfig;

/**
 * InformixDbConfig
 *
 */
class InformixDbConfig extends SqlDbConfig
{
    public static function getDriverName()
    {
        return 'informix';
    }

    public static function getDefaultPort()
    {
        return 1526;
    }
}