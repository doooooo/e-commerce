<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link rel="stylesheet" href="stlowRe.css">
</head>
<body>
    <div class="auth-container">
        <h2 class="auth-title">Registration Form</h2>

        <!-- عرض رسائل الخطأ أو النجاح -->
        <?php 
        if (isset($_GET['error'])): ?>
            <p class="auth-message error"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>

        <?php
         if (isset($_GET['success'])): ?>
            <p class="auth-message success"><?php echo htmlspecialchars($_GET['success']); ?></p>
        <?php endif; ?>

        <!-- نموذج التسجيل -->
        <form action="register.php" method="POST" class="auth-form">
            <label for="username">Full Name:</label>
            <input type="text" id="username" name="username" required>

           <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
          
            <button type="submit" class="auth-button">Register</button>
        </form>
        <p class="auth-footer">Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
