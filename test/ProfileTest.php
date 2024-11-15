use PHPUnit\Framework\TestCase;

class ProfileTest extends TestCase
{
    protected $dbh;

    protected function setUp(): void
    {
        // Mock PDO for database interaction
        $this->dbh = $this->getMockBuilder(PDO::class)
                          ->disableOriginalConstructor()
                          ->getMock();
    }

    public function testProfileUpdateSuccess()
    {
        // Simulate session and POST data
        $_SESSION['login'] = 'user@example.com';
        $_POST['fullname'] = 'John Doe';
        $_POST['address'] = '123 Main St';
        $_POST['country'] = 'USA';
        $_POST['city'] = 'New York';

        // Mock successful database update
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('rowCount')->willReturn(1);

        $this->dbh->method('prepare')->willReturn($stmt);

        ob_start();
        include 'profile.php';
        $output = ob_get_clean();

        // Check if "Save Changes" was successful
        $this->assertStringContainsString('Save Changes', $output);
    }

    public function testProfileUpdateFail()
    {
        // Simulate session and POST data with missing required fields
        $_SESSION['login'] = 'user@example.com';
        $_POST['fullname'] = ''; // Missing full name

        // Mock PDO prepare to return failure in database update
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->method('rowCount')->willReturn(0); 

        $this->dbh->method('prepare')->willReturn($stmt);

        ob_start();
        include 'profile.php';
        $output = ob_get_clean();

        // Assert form redisplays due to missing full name
        $this->assertStringContainsString('Please fill out this field', $output);
    }

    protected function tearDown(): void
    {
        unset($_SESSION['login'], $_POST['fullname'], $_POST['address'], $_POST['country'], $_POST['city']);
    }
}
