<?php

use PHPUnit\Framework\TestCase;
use PDO;
use PDOException;
use PDOStatement;

class DatabaseConnectionTest extends TestCase
{
    private $dbh;
    public function setUp(): void
    {
        $pdoStatementMock = $this->createMock(PDOStatement::class);
        $this->dbh = $this->createMock(PDO::class);
        $this->dbh->method('prepare')->willReturn($pdoStatementMock);
    }
    public function testDatabaseConnectionSuccess()
    {
        $this->assertInstanceOf(PDO::class, $this->dbh);
    }


    public function testDatabaseConnectionFailure()
    {

        $this->expectException(PDOException::class);
        $dbh = new PDO('mysql:host=invalid_host;dbname=invalid_db', 'invalid_user', 'invalid_pass');
    }
}
