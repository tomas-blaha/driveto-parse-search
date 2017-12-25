<?php

use Tester\TestCase;

$container = require __DIR__ . "/bootstrap.php";


class Base extends TestCase {

    protected $connection;
    protected $container;
    protected $context;

    /**
     * Prepare neccesary services
     */
    public function setUp() {
        $this->container = require __DIR__ . "/bootstrap.php";
        $this->connection = new \Nette\Database\Connection("mysql:host=localhost;dbname=driveto_mock", "root", "");
        $storage = new Nette\Caching\Storages\DevNullStorage();
        $structure = new Nette\Database\Structure($this->connection, $storage);
        $this->context = new \Nette\Database\Context($this->connection, $structure);
    }

    /**
     * Remove all tables from mock database
     */
    public function clearDatabase() {
        $this->connection->query("SET foreign_key_checks = 0; DROP TABLE car, engine, make, model, offer, trim");
    }

    /**
     * Create tables in mock database
     */
    public function insertEmptyData() {
        Nette\Database\Helpers::loadFromFile($this->connection, __DIR__ . '/db/driveto_mock_empty.sql');
    }
    
    /**
     * Create tables in mock database with included data
     */
    public function insertMockData() {
        Nette\Database\Helpers::loadFromFile($this->connection, __DIR__ . '/db/driveto_mock_data.sql');
    }

}
