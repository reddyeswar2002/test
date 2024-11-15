<?php

use PHPUnit\Framework\TestCase;

class NewsletterSubscriptionTest extends TestCase
{
    protected $dbh;

    protected function setUp(): void
    {
        // Set up a mock database connection using PDO
        $this->dbh = $this->createMock(PDO::class);
        $GLOBALS['dbh'] = $this->dbh;
    }

    public function testSubscribeSuccess()
    {
        // Arrange
        $subscriberEmail = 'newuser@example.com';

        // Mocking the behavior of database queries
        $stmt = $this->createMock(PDOStatement::class);
        $this->dbh->method('prepare')->willReturn($stmt);
        
        // Expect the SELECT statement
        $stmt->expects($this->once())
            ->method('bindParam')
            ->with($this->equalTo(':subscriberemail'), $this->equalTo($subscriberEmail), $this->equalTo(PDO::PARAM_STR));
        
        $stmt->method('execute')->willReturn(true);
        $stmt->method('rowCount')->willReturn(0); // No existing subscription

        // Mocking the insert statement
        $insertStmt = $this->createMock(PDOStatement::class);
        $this->dbh->method('prepare')->willReturn($insertStmt);
        
        $insertStmt->expects($this->once())
            ->method('bindParam')
            ->with($this->equalTo(':subscriberemail'), $this->equalTo($subscriberEmail), $this->equalTo(PDO::PARAM_STR));
        
        $insertStmt->method('execute')->willReturn(true);
        
        // Mocking the lastInsertId method
        $this->dbh->method('lastInsertId')->willReturn(1); // Simulate a successful insert

        // Act
        $_POST['emailsubscibe'] = true;
        $_POST['subscriberemail'] = $subscriberEmail;
        include 'C:/xampps/htdocs/Online Car Rental/includes/footer.php'; // Include the footer.php script

        // Assert
        $this->expectOutputRegex('/Subscribed successfully/');
    }

    public function testSubscribeFailure()
    {
        // Arrange
        $subscriberEmail = 'existinguser@example.com';

        // Mocking the behavior of database queries
        $stmt = $this->createMock(PDOStatement::class);
        $this->dbh->method('prepare')->willReturn($stmt);
        
        $stmt->expects($this->once())
            ->method('bindParam')
            ->with($this->equalTo(':subscriberemail'), $this->equalTo($subscriberEmail), $this->equalTo(PDO::PARAM_STR));
        
        $stmt->method('execute')->willReturn(true);
        $stmt->method('rowCount')->willReturn(1); // Existing subscription

        // Act
        $_POST['emailsubscibe'] = true;
        $_POST['subscriberemail'] = $subscriberEmail;
        include 'C:/xampps/htdocs/Online Car Rental/includes/footer.php'; // Include the footer.php script

        // Assert
        $this->expectOutputRegex('/Already Subscribed/');
    }

    public function testAlreadySubscribed()
    {
        // Arrange
        $subscriberEmail = 'existing@example.com';

        // Mocking the behavior of database queries
        $stmt = $this->createMock(PDOStatement::class);
        $this->dbh->method('prepare')->willReturn($stmt);
        
        $stmt->expects($this->once())
            ->method('bindParam')
            ->with($this->equalTo(':subscriberemail'), $this->equalTo($subscriberEmail), $this->equalTo(PDO::PARAM_STR));
        
        $stmt->method('execute')->willReturn(true);
        $stmt->method('rowCount')->willReturn(1); // Existing subscription

        // Act
        $_POST['emailsubscibe'] = true;
        $_POST['subscriberemail'] = $subscriberEmail;
        include 'C:/xampps/htdocs/Online Car Rental/includes/footer.php'; // Include the footer.php script

        // Assert
        $this->expectOutputRegex('/Already Subscribed/');
    }

    protected function tearDown(): void
    {
        // Clean up after tests if necessary
        unset($GLOBALS['dbh']);
    }
}
