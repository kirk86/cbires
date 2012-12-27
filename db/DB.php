<?php

/**
 * Database class, DB.php
 * Provides a PDO wrapper
 * around PostgreSQL database.
 * 
 * @category  classes
 * @author    John Mitros
 * @copyright 2012
 */


class DB
{
    // Hold instance of PDO wrapper
    private static $instance;

    /**
     * Prevent direct creation of object
     * 
     * @param empty
     * @return empty
     */
    private function __construct() { }

    /**
     * Create new PDO instance
     * 
     * @param empty
     * @return object, PDO instance
     */
    private static function getInstance()
    {
        if (!isset(self::$instance))
        {
            try
            {
                self::$instance = new PDO(PDO_DSN);

                // Configure PDO to throw exceptions
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            }
            catch (PDOException $e)
            {
                // Close the database handler and trigger an error
                self::Close();
                trigger_error($e->getMessage(), E_USER_ERROR);
            }
        }

        return self::$instance;
    }

    /**
     * Close db connection
     * 
     * @param empty
     * @return empty
     */
    public static function close()
    {
        self::$instance = null;
    }

    /**
     * Execute queries such as INSERT,UPDATE
     * Wrapper method for PDOStatement::execute()
     * 
     * @param string $query SQL query
     * @param array $params parameters for prepared named SQL statements
     * @return string affected rows
     */
    public static function execute($query, $params = null)
    {
        try
        {
            $db        = self::getInstance();
            $statement = $db->prepare($query);
            $statement->execute($params);

        }
        catch (PDOException $e)
        {
            self::Close();
            trigger_error($e->getMessage(), E_USER_ERROR);
        }

        return $statement->rowCount();
    }

    /**
     * Wrapper method for PDOStatement::fetchAll()
     * 
     * @param string $query SQL query
     * @param array $params parameteres for prepared named SQL statements
     * @param constant $fetchStyle fetch mode for PDO
     */
    public static function getAll($query, $params = null, $fetchStyle = PDO::FETCH_ASSOC)
    {
        $result = null;

        try
        {
            $db        = self::getInstance();
            $statement = $db->prepare($query);
            $statement->execute($params);
            $result    = $statement->fetchAll($fetchStyle);
                 
        }
        catch (PDOException $e) {
            self::Close();
            trigger_error($e->getMessage(), E_USER_ERROR);
        }

        return $result;
        //return $clean_result;
    }
    
    /**
     * Wrapper method for PDOStatement::fetch()
     * 
     * @param string $query SQL query
     * @param array $params parameteres for prepared named SQL statements
     * @param constant $fetchStyle fetch mode for PDO
     */
    public static function getRow($query, $params = null, $fetchStyle = PDO::FETCH_ASSOC)
    {
        $result = null;

        try {
            $db = self::getInstance();

            $statement = $db->prepare($query);

            $statement->execute($params);

            $result = $statement->fetch($fetchStyle);
        }
        catch (PDOException $e) {
            self::Close();
            trigger_error($e->getMessage(), E_USER_ERROR);
        }

        return $result;
    }
    
    /**
     * Return the first column value from a row
     * 
     * @param string $query SQL query
     * @param array $params parameteres for prepared named SQL statements
     */
    public static function getOne($query, $params = null)
    {
        $result = null;

        try {
            $db = self::getInstance();

            $statement = $db->prepare($query);

            $statement->execute($params);

            $result = $statement->fetch(PDO::FETCH_NUM);
        }
        catch (PDOException $e) {
            self::Close();
            trigger_error($e->getMessage(), E_USER_ERROR);
        }

        return $result;
    }
}
