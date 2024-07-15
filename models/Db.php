<?php

/**
 * Class Db
 *
 * A static class handling database operations using PDO.
 */
class Db
{
    private static PDO $connection;

    private static array $settings = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        PDO::ATTR_EMULATE_PREPARES => false,
    );

    /**
     * Establishes a database connection if it does not already exist.
     *
     * @param string $host     The database host.
     * @param string $user     The database username.
     * @param string $password The database password.
     * @param string $database The database name.
     * @return void
     */
    public static function getConnection(string $host, string $user, string $password, string $database): void {
        if(!isset(self::$connection)) {
            self::$connection = @new PDO(
                "mysql:host=$host;dbname=$database",
                $user,
                $password,
                self::$settings
            );
        }
    }

    /**
     * Executes a SQL query that is expected to return a single row.
     *
     * @param string $sql        The SQL query.
     * @param array $parameters  Optional parameters for prepared statement.
     * @return array|bool        Returns the fetched row as an array or false on failure.
     */
    public static function queryOne(string $sql, array $parameters = array()): array|bool {
        $return = self::$connection->prepare($sql);
        $return->execute($parameters);
        return $return->fetch();
    }

    /**
     * Executes a SQL query that is expected to return multiple rows.
     *
     * @param string $sql        The SQL query.
     * @param array $parameters  Optional parameters for prepared statement.
     * @return array|bool        Returns an array of fetched rows or false on failure.
     */
    public static function queryAll(string $sql, array $parameters = array()): array|bool {
        $return = self::$connection->prepare($sql);
        $return->execute($parameters);
        return $return->fetchAll();
    }

    /**
     * Executes a SQL query that is expected to return a single scalar value.
     *
     * @param string $sql        The SQL query.
     * @param array $parameters  Optional parameters for prepared statement.
     * @return string           Returns the fetched scalar value as a string.
     */
    public static function queryItself(string $sql, array $parameters = array()): string {
        $result = self::queryOne($sql, $parameters);
        return $result[0];
    }

    /**
     * Executes a SQL query that does not return data, e.g., INSERT, UPDATE, DELETE.
     *
     * @param string $sql        The SQL query.
     * @param array $parameters  Optional parameters for prepared statement.
     * @return int               Returns the number of affected rows.
     */
    public static function query(string $sql, array $parameters = array()): int {
        $return = self::$connection->prepare($sql);
        $return->execute($parameters);
        return $return->rowCount();
    }

    /**
     * Executes an INSERT statement.
     *
     * @param string $table      The table to insert into.
     * @param array $parameters  Key-value pairs of columns and values to insert.
     * @return bool              Returns true on success, false on failure.
     */
    public static function insert(string $table, array $parameters = array()): bool {
        return self::query("INSERT INTO `$table` (`".
            implode('`, `', array_keys($parameters)).
            "`) VALUES (".str_repeat('?,', sizeOf($parameters)-1)."?)",
                array_values($parameters));
    }

    /**
     * Executes an UPDATE statement.
     *
     * @param string $table      The table to update.
     * @param array $values      Key-value pairs of columns and new values.
     * @param string $condition  The WHERE clause condition.
     * @param array $parameters  Optional parameters for prepared statement.
     * @return bool              Returns true on success, false on failure.
     */
    public static function update(string $table, array $values, string $condition, array $parameters = array()): bool {
        return self::query("UPDATE `$table` SET `".
        implode('` = ?, `', array_keys($values)).
        "` = ? " . $condition,
        array_merge(array_values($values), $parameters));
    }

    /**
     * Retrieves the last inserted ID from the database connection.
     *
     * @return int               Returns the last inserted ID.
     */
    public static function lastInsertId(): int {
        return self::$connection->lastInsertId();
    }
}