use PHPUnit\Framework\TestCase;

class UpdatePasswordTest extends TestCase
{
    protected $dbh;

    protected function setUp(): void
    {
        // Create a mock for the PDO object to simulate database interaction
        $this->dbh = $this->getMockBuilder(PDO::class)
                          ->disableOriginalConstructor()
                          ->getMock();
    }

    public function testPasswordChangeSuccess()
    {
        // Simulate session and POST data
        $_SESSION['login'] = 'user@example.com';
        $_POST['password'] = 'oldpassword';
        $_POST['newpassword'] = 'newpassword';
        
        // Mock the PDO statement to return successful password match
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('rowCount')->willReturn(1); // Simulate password match

        // Mock the prepare() method to return the mocked statement
        $this->dbh->method('prepare')->willReturn($stmt);
        
        // Simulate successful password change
        ob_start();
        include 'update-password.php';
        $output = ob_get_clean();
        
        // Check if success message is in the output
        $this->assertStringContainsString('Your Password successfully changed', $output);
    }

    public function testPasswordChangeFail()
    {
        // Simulate session and POST data
        $_SESSION['login'] = 'user@example.com';
        $_POST['password'] = 'wrongpassword';
        $_POST['newpassword'] = 'newpassword';

        // Mock the PDO statement to return no match (invalid current password)
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('rowCount')->willReturn(0); // Simulate password mismatch

        $this->dbh->method('prepare')->willReturn($stmt);
        
        ob_start();
        include 'update-password.php';
        $output = ob_get_clean();

        // Check if error message is in the output
        $this->assertStringContainsString('Your current password is wrong', $output);
    }

    protected function tearDown(): void
    {
        unset($_SESSION['login'], $_POST['password'], $_POST['newpassword']);
    }
}
