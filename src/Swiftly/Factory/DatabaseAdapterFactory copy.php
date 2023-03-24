<?php

namespace Swiftly\Factory;

use Swiftly\Config\Store;
use Swiftly\Database\AdapterInterface;
use Swiftly\Database\Adapter\MysqlAdapter;
use Swiftly\Database\Adapter\PostgresAdapter;
use Swiftly\Database\Adapter\SqliteAdapter;

use function strtolower;

/**
 * Factory used to create the appropriate database adapter for this environment
 * 
 * @since 1.0-beta To reduce clutter in services.php
 */
final class DatabaseWrapperFactory
{
    /**
     * Reads the application config and creates the appropriate adapter
     */
    public static function create(Store $config): ?AdapterInterface
    {
        if (!$config->has('database') || !$config->has('database.adapter')) {
            // todo: Should probably throw here
            return null;
        }

        $type = (string)$config->get('database.adapter');

        $adapter_type = self::inferType($type);

        return new $adapter_type();
    }

    /**
     * Infer the type of adapter needed from the config string
     * 
     * Can swap to using match when we get to PHP 8
     * 
     * @psalm-return class-string<AdapterInterface>
     * 
     * @return string Adapter class name
     */
    private static function inferType(string $type): string
    {
        switch (strtolower($type)) {
            case 'mysql':
            case 'mysqli':
                return MysqlAdapter::class;
            case 'postgres':
            case 'postgresql':
                return PostgresAdapter::class;
            case 'sqlite':
                return SqliteAdapter::class;
            default:
                // TODO: Should probably throw instead?
                return MysqlAdapter::class;
        }
    }
}
