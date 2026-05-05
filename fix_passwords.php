<?php
require_once "classes/Database.php";

try {
    $db = (new Database())->getConnection();

    // 1. Generate new hashes
    $adminHash = password_hash("admin123", PASSWORD_DEFAULT);
    $userHash  = password_hash("password123", PASSWORD_DEFAULT);

    // 2. Update Admin
    $stmt1 = $db->prepare("UPDATE users SET password = ? WHERE email = 'admin@example.com'");
    $stmt1->execute([$adminHash]);

    // 3. Update John Doe
    $stmt2 = $db->prepare("UPDATE users SET password = ? WHERE email = 'john@example.com'");
    $stmt2->execute([$userHash]);

    echo "<h2>✅ Passwords updated successfully!</h2>";
    echo "<p>You can now log in with:</p>";
    echo "<ul>
            <li><strong>admin@example.com</strong> / admin123</li>
            <li><strong>john@example.com</strong> / password123</li>
          </ul>";
    echo "<a href='login.php'>Go to Login Page</a>";

} catch (Exception $e) {
    echo "<h2>❌ Error</h2>";
    echo $e->getMessage();
}
