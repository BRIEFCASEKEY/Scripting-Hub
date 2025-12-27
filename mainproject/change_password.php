<?php
session_start();
require_once "../project-folder/connect.php";

/* Protect page */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../project-folder/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

/* Handle password change */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($new_password !== $confirm_password) {
        $message = "New password and confirm password do not match!";
    } else {
        // Fetch current hashed password
        $stmt = mysqli_prepare($conn, "SELECT password FROM users WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($current_password, $user['password'])) {
            // Update password
            $new_hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE id = ?");
            mysqli_stmt_bind_param($update_stmt, "si", $new_hashed, $user_id);
            if (mysqli_stmt_execute($update_stmt)) {
                $message = "Password updated successfully!";
            } else {
                $message = "Failed to update password. Try again.";
            }
        } else {
            $message = "Current password is incorrect!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Change Password — Scripting Hub</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<style>
:root { --bg:#000; --muted:#bfc7d1; --neon:#00bfff; }
* {
 box-sizing:border-box; 
 font-family: "Poppins", sans-serif;
}
body { margin:0; font-family:Poppins,sans-serif; background:var(--bg); color:#fff; }
header { padding:28px 18px; text-align:center; }
.brand { font-size:1.6rem; font-weight:700; color:#fff; }
.tag { font-weight:300; color:var(--muted); font-size:0.9rem; margin-top:6px; }
nav { display:flex; gap:12px; justify-content:center; padding:12px 18px; }
.nav-link { color:var(--muted); text-decoration:none; padding:8px 12px; border-radius:10px; font-weight:600; }
.nav-link:hover { color:#fff; background:rgba(255,255,255,0.03); }

.wrap { max-width:500px; margin:40px auto; padding:0 18px; }
.password-card {
    position:relative;
    background: var(--bg);
    border-radius:14px;
    padding:25px;
    border:1px solid rgba(255,255,255,0.05);
    animation: floatIn 1.2s cubic-bezier(0.22,1,0.36,1) forwards;
    z-index:1;
}

/* 🌈 Rainbow border with subtle glow */
.password-card::before {
    content:"";
    position:absolute;
    inset:-2px;
    border-radius:16px;
    background: linear-gradient(120deg, #00eaff, #ad33ff, #99ff99, #ff944d);
    background-size:300% 300%;
    animation: rainbowBorder 6s linear infinite;
    box-shadow:
        0 0 6px rgba(0,234,255,0.35),
        0 0 10px rgba(173,51,255,0.25),
        0 0 14px rgba(255,148,77,0.15);
    z-index:-2;
}

/* Mask inside so content stays clean */
.password-card::after {
    content:"";
    position:absolute;
    inset:0;
    background: var(--bg);
    border-radius:14px;
    z-index:-1;
}

@keyframes rainbowBorder {
    0% { background-position:0% 50%; }
    50% { background-position:100% 50%; }
    100% { background-position:0% 50%; }
}

.password-card h2 { margin-top:0; margin-bottom:15px; }
.password-card label { display:block; font-size:0.85rem; margin-bottom:5px; color:var(--muted);}
.password-card input { width:100%; padding:10px; margin-bottom:15px; border-radius:10px; border:1px solid rgba(255,255,255,0.05); background: rgba(255,255,255,0.03); color:#fff; }
.password-card input:focus { outline:none; border-color:var(--neon); box-shadow:0 0 10px var(--neon); }
.password-card button { padding:10px 25px; border:none; border-radius:10px; background:var(--neon); color:#000; font-weight:600; cursor:pointer; transition:0.3s; }
.password-card button:hover { background:#00eaff; }

.message { margin-bottom:15px; font-size:0.95rem; color:#ff6b6b; }
.success { color:#4cd137; }
.actions { margin-top:20px; display:flex; gap:15px; justify-content:center; }
.actions a {
    text-decoration:none;
    padding:12px 25px;
    border-radius:12px;
    background:transparent;
    border:1px solid rgba(255,255,255,0.06);
    color:var(--muted);
    font-weight:600;
    transition:0.3s;
}
.actions a:hover { background: white; color:black; border-color:white; }

@keyframes floatIn { from { opacity:0; transform:translateY(35px);} to { opacity:1; transform:translateY(0);} }
.password-wrap {
  position: relative;
}

.password-wrap input {
  padding-right: 42px; /* space for eye */
}

.password-wrap .eye {
  position: absolute;
  right: 12px;
  top: 40%;
  transform: translateY(-50%);
  cursor: pointer;
  font-size: 1.1rem;
  color: var(--muted);
  user-select: none;
}

.password-wrap .eye:hover {
  color: white;
}

/* prevent glow on eye click */
.password-wrap .eye:active {
  transform: translateY(-50%) scale(0.95);
}
footer { margin-top:36px; padding:28px 18px; text-align:center; color:var(--muted); font-size:0.9rem; }
</style>
</head>
<body>

<header>
  <div class="brand">Scripting Hub</div>
  <div class="tag">Interactive lessons • Practice playground • Beginner-friendly</div>
</header>

<nav>
  <a class="nav-link" href="menu.html">Learn</a>
  <a class="nav-link" href="practice.html">Concepts</a>
  <a class="nav-link" href="about.html">About</a>
  <a class="nav-link" href="profile.php">Profile</a>
</nav>

<div class="wrap">
    <div class="password-card">
        <h2>Change Password</h2>

        <?php if($message): ?>
            <div class="message <?php echo ($message === "Password updated successfully!") ? 'success' : ''; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="current_password">Current Password</label>
<div class="password-wrap">
  <input type="password" name="current_password" id="current_password" required>
  <span class="eye" onclick="togglePassword('current_password', this)">👁</span>
</div>

            <label for="new_password">New Password</label>
<div class="password-wrap">
  <input type="password" name="new_password" id="new_password" required>
  <span class="eye" onclick="togglePassword('new_password', this)">👁</span>
</div>

            <label for="confirm_password">Confirm New Password</label>
<div class="password-wrap">
  <input type="password" name="confirm_password" id="confirm_password" required>
  <span class="eye" onclick="togglePassword('confirm_password', this)">👁</span>
</div>

            <button type="submit">Update Password</button>
        </form>

        <div class="actions">
            <a href="profile.php">Back to Profile</a>
        </div>
    </div>
</div>

<footer>
  &copy; 2025 Scripting Hub. All Rights Reserved.
</footer>
<script>
function togglePassword(id, el) {
  const field = document.getElementById(id);
  if (!field) return;

  if (field.type === "password") {
    field.type = "text";
    el.textContent = "🙈";
  } else {
    field.type = "password";
    el.textContent = "👁";
  }
}
</script>
</body>
</html>
