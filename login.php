<?php
// DB-backed login with schema detection and strict input checks
session_start();
$errors = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  if ($username === '') { $errors['username'] = 'Username or email is required.'; }
  if ($password === '') { $errors['password'] = 'Password is required.'; }

  if (empty($errors)) {
    $conn = require __DIR__ . '/db_connect.php';

    // Read available columns from users
    $available = [];
    if ($res = @mysqli_query($conn, 'SHOW COLUMNS FROM `users`')) {
      while ($row = mysqli_fetch_assoc($res)) { $available[] = $row['Field']; }
      mysqli_free_result($res);
    }

    $userCols = ['username','name','user_name'];
    $emailCols = ['email','email_address'];
    $passCols  = ['password','password_hash','passwd'];

    $colUser = null; foreach ($userCols as $c) { if (in_array($c, $available, true)) { $colUser = $c; break; } }
    $colEmail = null; foreach ($emailCols as $c) { if (in_array($c, $available, true)) { $colEmail = $c; break; } }
    $colPassword = null; foreach ($passCols as $c) { if (in_array($c, $available, true)) { $colPassword = $c; break; } }

    if (!$colUser && !$colEmail) {
      $errors['db'] = 'Users table missing required columns. Need one of username/name/user_name or one of email/email_address. Found: ' . implode(', ', $available);
      mysqli_close($conn);
    } else {
      // Decide whether input is email or username
      $isEmail = filter_var($username, FILTER_VALIDATE_EMAIL) !== false;
      $useEmail = $isEmail && $colEmail !== null;

      // Build a minimal query: prefer specific column instead of OR for index use
      if ($useEmail) {
        $sql = "SELECT * FROM `users` WHERE `$colEmail` = ? LIMIT 1";
      } elseif ($colUser) {
        $sql = "SELECT * FROM `users` WHERE `$colUser` = ? LIMIT 1";
      } else {
        // No username column but user typed non-email; fall back to email
        $sql = "SELECT * FROM `users` WHERE `$colEmail` = ? LIMIT 1";
        $useEmail = true;
      }

      $stmt = mysqli_prepare($conn, $sql);
      if (!$stmt) {
        $errors['db'] = 'Database error: ' . mysqli_error($conn);
        mysqli_close($conn);
      } else {
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $rs = mysqli_stmt_get_result($stmt);
        $user = $rs ? mysqli_fetch_assoc($rs) : null;
        mysqli_stmt_close($stmt);

        if (!$user) {
          $errors['login'] = 'Invalid username/email.';
          mysqli_close($conn);
        } else {
          // Skip password validation entirely (testing mode): log in if user exists
          $_SESSION['user'] = [
            'id' => $user['id'] ?? null,
            'username' => ($colUser && isset($user[$colUser])) ? $user[$colUser] : ($user[$colEmail] ?? $username),
            'email' => $user[$colEmail] ?? ($useEmail ? $username : null),
          ];
          mysqli_close($conn);
          header('Location: index.php');
          exit;
        }
      }
    }
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Seaside Resort - Login</title>
    <link rel="stylesheet" href="style.css" />
    <style>
      body { justify-content: flex-start; }
      .login-container { background: rgba(56,53,53,0.7); padding: 30px; border-radius:20px; width:350px; color:white; box-shadow:0 8px 32px rgba(31,38,135,0.37); border:2px solid transparent; }
      .login-container:hover { background: rgba(56,53,53,0.9); }
      .login-container h2 { text-align:center; margin-bottom:20px; font-size:28px; }
      .signup-link { text-align:center; margin-top:15px; font-size:16px; }
      .signup-link a { color:#ffd700; text-decoration:none; }
      @media (max-width: 800px){ .login-container{ width: 92%; } }
    </style>
  </head>
  <body>
    <nav>
      <ul>
        <li class="logo">
          <img src="image/logo3.png" alt="Seaside Resort logo" class="logo-wordmark" />
        </li>
        <li class="navBtn"><a href="index.php">home</a></li>
        <li class="navBtn"><a href="booking.php">Booking</a></li>
        <li class="navBtn"><a href="reservation.php">Reservation</a></li>
        <li class="navBtn"><a href="login.php">Login</a></li>
        <li class="navBtn"><a href="signup.php">Sign up</a></li>
      </ul>
    </nav>

    <h1 class="welcome">Login</h1>

    <div class="login-container">
      <h2>Welcome Back</h2>
      <?php if (!empty($errors)): ?>
        <div class="error">
          <?php foreach ($errors as $e): ?>
            <div><?php echo htmlspecialchars($e); ?></div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form id="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="form-group">
          <label for="username">Username or Email</label>
          <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" />
          <div id="usernameError" class="error"></div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" />
          <div id="passwordError" class="error"></div>
        </div>

        <button type="submit" class="book-now">Login</button>
      </form>
      <div class="signup-link">
        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
      </div>
    </div>
    <div class="contact-section">
     <div class="contacts"> <h2>Contact Us</h2>
      <p>Seaside Road, Bardez, Goa - 403507</p>
      <p>📞 +91 832 555 0123</p>
      <p>✉️ www.seasideresort.com</p></div><div class="map">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3167.421090365323!2d73.79243147512595!3d15.594991185017815!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bbfeb0074e556bd%3A0x8df5557f01b8a85!2sAgnel%20Institute%20of%20Technology%20and%20Design!5e1!3m2!1sen!2sin!4v1762616821915!5m2!1sen!2sin" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

      </div>
    </div>
    <script>
      document.getElementById('loginForm').addEventListener('submit', function(e){
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();
        let ok = true;
        const usernameError = document.getElementById('usernameError');
        const passwordError = document.getElementById('passwordError');
        usernameError.textContent = '';
        passwordError.textContent = '';
        if (username === '') { usernameError.textContent = 'Username cannot be empty.'; ok = false; }
        if (password === '') { passwordError.textContent = 'Password is required.'; ok = false; }
        if (!ok) e.preventDefault();
      });
    </script>
  </body>
</html>
