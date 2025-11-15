<?php
$errors = [];
$username = $email = $mobile = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $mobile = trim($_POST['mobile'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirm = $_POST['confirmPassword'] ?? '';

  if ($username === '' || !preg_match('/^[A-Za-z][A-Za-z0-9]{2,9}$/', $username)) {
    $errors['username'] = 'Username must start with a letter and be 3–10 chars.';
  }
  if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Valid email is required.';
  }
  if ($mobile === '' || !preg_match('/^[1-9][0-9]{9}$/', $mobile)) {
    $errors['mobile'] = 'Enter valid 10-digit phone (not starting with 0).';
  }
  if ($password === '' || !preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*(),.?"":{}|<>]).{8,}$/', $password)) {
    $errors['password'] = 'Password must be at least 8 chars, include uppercase, lowercase, digit & special char.';
  }
  if ($confirm !== $password) {
    $errors['confirmPassword'] = 'Passwords do not match.';
  }

  if (empty($errors)) {
    $conn = require __DIR__ . '/db_connect.php';
    // TEST MODE: store plaintext password (do not use in production)
    $storedPassword = $password;

    $available = [];
    $colsRes = @mysqli_query($conn, "SHOW COLUMNS FROM `users`");
    if ($colsRes) {
      while ($r = mysqli_fetch_assoc($colsRes)) {
        $available[] = $r['Field'];
      }
      mysqli_free_result($colsRes);
    }

    $colUsername = in_array('username', $available) ? 'username' : (in_array('name', $available) ? 'name' : (in_array('user_name', $available) ? 'user_name' : null));
    $colEmail = in_array('email', $available) ? 'email' : (in_array('email_address', $available) ? 'email_address' : null);
    $colPhone = in_array('phone', $available) ? 'phone' : (in_array('phone_number', $available) ? 'phone_number' : (in_array('mobile', $available) ? 'mobile' : null));
    $colPassword = in_array('password', $available) ? 'password' : (in_array('password_hash', $available) ? 'password_hash' : (in_array('passwd', $available) ? 'passwd' : null));

    if (!$colUsername || !$colEmail || !$colPhone || !$colPassword) {
      $errors['db'] = 'Users table schema unexpected. Expected columns like username/email/phone/password. Found: ' . implode(', ', $available);
      mysqli_close($conn);
    } else {
      $fields = [$colUsername, $colEmail, $colPhone, $colPassword];
      $placeholders = implode(',', array_fill(0, count($fields), '?'));
      $sql = 'INSERT INTO `users` (' . implode(', ', $fields) . ") VALUES ($placeholders)";

      $stmt = mysqli_prepare($conn, $sql);
      if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ssss', $username, $email, $mobile, $storedPassword);
        $ok = mysqli_stmt_execute($stmt);
        if ($ok) {
          mysqli_stmt_close($stmt);
          mysqli_close($conn);
          header('Location: login.php');
          exit;
        } else {
          $errors['db'] = 'Could not create account. Error: ' . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
      } else {
        $errors['db'] = 'Database error: ' . mysqli_error($conn);
      }
      mysqli_close($conn);
    }
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Seaside Resort - Sign Up</title>
    <link rel="stylesheet" href="style.css" />
    <style>
  body { justify-content: flex-start; }
  .signup-container {
    background: rgba(56, 53, 53, 0.7);
    padding: 30px;
    border-radius: 20px;
    width: 400px;
    color: white;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    border: 2px solid transparent;
    transition: 0.3s ease;
  }
  .signup-container:hover {
    animation: bordercolor 20s infinite;
    background: rgba(56, 53, 53, 0.9);
  }
  .signup-container h2 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 28px;
  }
  .login-link {
    text-align: center;
    margin-top: 15px;
    font-size: 16px;
  }
  .login-link a {
    color: #ffd700;
    text-decoration: none;
  }
  .login-link a:hover { text-decoration: underline; }
  @media (max-width: 800px){ .signup-container{ width:92%; } }
    </style>
  </head>
  <body>
    <nav>
      <ul>
        <li class="logo">
          <img src="image/logo3.png" alt="Seaside Resort logo" class="logo-wordmark" />
        </li>
        <li class="navBtn"><a href="index.php">home</a></li>
        <li class="navBtn"><a href="login.php">Login</a></li>
        <li class="navBtn"><a href="signup.php">Sign up</a></li>
      </ul>
    </nav>


    <div class="signup-container">
      <h2>Sign Up</h2>
      <?php if (!empty($errors)): ?>
        <div class="error">
          <?php foreach ($errors as $e): ?>
            <div><?php echo htmlspecialchars($e); ?></div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <!-- server-side success message removed; on success user is redirected to login.php -->

      <form id="regForm" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" />
          <div id="usernameError" class="error"></div>
        </div>

        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" />
          <div id="emailError" class="error"></div>
        </div>

        <div class="form-group">
          <label for="mobile">Phone Number</label>
          <input type="tel" id="mobile" name="mobile" value="<?php echo htmlspecialchars($mobile); ?>" />
          <div id="mobileError" class="error"></div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" />
          <div id="passwordError" class="error"></div>
        </div>

        <div class="form-group">
          <label for="confirmPassword">Confirm Password</label>
          <input type="password" id="confirmPassword" name="confirmPassword" />
          <div id="confirmPasswordError" class="error"></div>
        </div>

        <button type="submit" class="book-now">Sign Up</button>
      </form>
      <div class="login-link">
        <p>Already have an account? <a href="login.php">Login</a></p>
      </div>
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
      document.getElementById('regForm').addEventListener('submit', function(event){
        let isValid = true;
        const username = document.getElementById('username').value.trim();
        const usernameError = document.getElementById('usernameError');
        if (username === '') { usernameError.textContent = 'Username cannot be empty.'; isValid = false; }
        else if (!/^[A-Za-z][A-Za-z0-9]{2,9}$/.test(username)) { usernameError.textContent = 'Username must start with a letter (3–10 chars).'; isValid = false; }
        else { usernameError.textContent = ''; }

        const email = document.getElementById('email').value.trim();
        const emailError = document.getElementById('emailError');
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email === '') { emailError.textContent = 'Email cannot be empty.'; isValid = false; }
        else if (!emailPattern.test(email)) { emailError.textContent = 'Invalid email format.'; isValid = false; }
        else { emailError.textContent = ''; }

        const mobile = document.getElementById('mobile').value.trim();
        const mobileError = document.getElementById('mobileError');
        if (mobile === '') { mobileError.textContent = 'Mobile cannot be empty.'; isValid = false; }
        else if (!/^[1-9][0-9]{9}$/.test(mobile)) { mobileError.textContent = 'Enter valid 10-digit number (not starting with 0).'; isValid = false; }
        else { mobileError.textContent = ''; }

        const password = document.getElementById('password').value.trim();
        const passwordError = document.getElementById('passwordError');
        const passwordPattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/;
        if (password === '') { passwordError.textContent = 'Password is required.'; isValid = false; }
        else if (!passwordPattern.test(password)) { passwordError.textContent = 'Password must be at least 8 chars, include uppercase, lowercase, digit & special character.'; isValid = false; }
        else { passwordError.textContent = ''; }

        const confirmPassword = document.getElementById('confirmPassword').value;
        const confirmPasswordError = document.getElementById('confirmPasswordError');
        if (confirmPassword !== password) { confirmPasswordError.textContent = 'Passwords do not match.'; isValid = false; }
        else { confirmPasswordError.textContent = ''; }

  

        if (!isValid) {
          event.preventDefault();
        }
      });
 
    </script>
  
  </body>
</html>
