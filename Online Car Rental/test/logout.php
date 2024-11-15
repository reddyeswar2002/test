use PHPUnit\Framework\TestCase;

class LogoutTest extends TestCase
{
    public function testLogout()
    {
        // Simulate session data
        $_SESSION['login'] = 'user@example.com';

        ob_start();
        include 'logout.php';
        $output = ob_get_clean();

        // Assert that session is destroyed
        $this->assertEmpty($_SESSION);

        // Check if the script redirects to 'index.php'
        $this->assertContains('location:index.php', $output);
    }
}
