<!DOCTYPE html>
<html>
  <head>
    <title>Seaside Resort - Sign Up</title>
    <link rel="stylesheet" href="style.css" />
  <style>
  /* Set a specific justify-content for login/signup pages */
  body {
    justify-content: center;
  }
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
  .login-link a:hover {
    text-decoration: underline;
  }
</style>
  </head>
  <body>
    <nav>
      <ul>
        <li class="logo">
          <img
            src="image/logo2.png"
            alt="Seaside Resort logo"
            class="logo-wordmark"
          />
        </li>
        <li class="navBtn"><a href="index.html">home</a></li>
        <li class="navBtn"><a href="login.html">Login</a></li>
        <li class="navBtn"><a href="signup.html">Sign up</a></li>
      </ul>
    </nav>

    <h1 class="welcome">Create Account</h1>

    <div class="signup-container">
      <h2>Sign Up</h2>
      <form>
        <div class="form-group">
          <label for="name">Full Name</label>
          <input type="text" id="name" placeholder="Enter your name" required />
        </div>
        <div class="form-group">
          <label for="email">Email Address</label>
          <input
            type="email"
            id="email"
            placeholder="Enter your email"
            required
          />
        </div>
        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input
            type="tel"
            id="phone"
            placeholder="Enter your phone number"
            required
          />
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input
            type="password"
            id="password"
            placeholder="Create a password"
            required
          />
        </div>
        <div class="form-group">
          <label for="confirm">Confirm Password</label>
          <input
            type="password"
            id="confirm"
            placeholder="Re-enter password"
            required
          />
        </div>

        <a style="text-decoration: none; color: white" href="index.html"
          ><button type="submit" class="book-now">Sign Up</button></a
        >
      </form>
      <div class="login-link">
        <p>
          Already have an account?
          <a href="login.html">Login</a>
        </p>
      </div>
    </div>
    <div class="contact-section">
      <h2>Contact Us</h2>
      <p>Seaside Road, Bardez, Goa - 403507</p>
      <p>📞 +91 832 555 0123</p>
      <p>✉️ www.seasideresort.com</p>
    </div>
    <script src="signup.js"></script>
  </body>
</html>
