<?php

namespace DreamFactory\Core\Informix\Database\Connectors;

use Illuminate\Database\Connectors\Connector;
use Illuminate\Database\Connectors\ConnectorInterface;

class InformixConnector extends Connector implements ConnectorInterface
{
    /**
     * Establish a database connection.
     *
     * @param  array $config
     * @return \PDO
     */
    public function connect(array $config)
    {
        $dsn = $this->getDsn($config);

        $options = $this->getOptions($config);
        $connection = $this->createConnection($dsn, $config, $options);

        if (isset($config['schema'])) {
            $schema = $config['schema'];

            $connection->prepare("set schema $schema")->execute();
        }

        return $connection;
    }

    /**
     * Create a DSN string from a configuration.
     *
     * @param  array $config
     * @return string
     */
    protected function getDsn(array $config)
    {
        extract($config, EXTR_SKIP);

        $dsn = "informix:";

        if (!empty($host)) {
            $dsn .= "HOST={$host};";
        }
        if (!empty($service)) {
            $dsn .= "SERVICE={$service};";
        } elseif (!empty($port)) {
            $dsn .= "SERVICE={$port};";
        }
        if (!empty($server)) {
            $dsn .= "SERVER={$server};";
        }
        if (!empty($database)) {
            $dsn .= "DATABASE={$database};";
        }
        if (!empty($protocol)) {
            $dsn .= "PROTOCOL={$protocol};";
        } else {
            $dsn .= "PROTOCOL=onsoctcp;";
        }

        $dsn .= "EnableScrollableCursors=1;";
//        $dsn = "informix:host=127.0.0.1;service=ol_informix1210;database=stores_demo;server=ol_informix1210;protocol=onsoctcp;EnableScrollableCursors=1;";

        return $dsn;
    }
}
