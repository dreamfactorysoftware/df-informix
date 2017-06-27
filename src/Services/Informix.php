<?php

namespace DreamFactory\Core\Informix\Services;

use DreamFactory\Core\SqlDb\Services\SqlDb;

/**
 * Class Informix
 *
 * @package DreamFactory\Core\SqlDb\Services
 */
class Informix extends SqlDb
{
    public static function adaptConfig(array &$config)
    {
        $config['driver'] = 'informix';
        parent::adaptConfig($config);
    }
}