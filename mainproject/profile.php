<?php
session_start();
require_once "../project-folder/connect.php";

/* Protect page */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../project-folder/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* Fetch user data */
$stmt = mysqli_prepare($conn, "SELECT firstName, lastName, email FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user   = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Scripting Hub — Profile</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<style>
:root {
  --bg:#000;
  --card:#0f1113;
  --muted:#bfc7d1;
  --neon:#00bfff;
}
@keyframes floatIn { from { opacity:0; transform:translateY(35px);} to { opacity:1; transform:translateY(0);} }

* {
 box-sizing:border-box; 
 font-family: "Poppins", sans-serif;
}
body { margin:0; background:var(--bg); color:#fff; font-family:Poppins, sans-serif; }

/* HEADER & NAVBAR */
header { padding:28px 18px; text-align:center; }
.brand { font-size:1.6rem; font-weight:700; letter-spacing:1px; color:#fff; }
.tag { font-weight:300; color:var(--muted); font-size:0.9rem; margin-top:6px; }
nav { display:flex; gap:12px; justify-content:center; padding:12px 18px; }
.nav-link { color:var(--muted); text-decoration:none; padding:8px 12px; border-radius:10px; font-weight:600; }
.nav-link:hover { color:#fff; background:rgba(255,255,255,0.03); }

/* PROFILE CARD */
.wrap { max-width:900px; margin:40px auto; padding:0 18px; display:flex; flex-direction:column; align-items:center; }
.profile-card {
  position:relative;
  display:flex;
  flex-direction:column;
  align-items:center;
  background: var(--card);
  border-radius:20px;
  padding:30px;
  width:100%;
  max-width:700px;
  animation: floatIn 1.2s cubic-bezier(0.22,1,0.36,1) forwards;
  z-index:1;
}

/* 🌈 Rainbow gradient border with subtle glow */
.profile-card::before {
  content:"";
  position:absolute;
  inset:-2px;
  border-radius:22px;
  background: linear-gradient(
    120deg,
    #00eaff,
    #ad33ff,
    #99ff99,
    #ff944d
  );
  background-size:300% 300%;
  animation: rainbowBorder 6s linear infinite;
  box-shadow:
    0 0 6px rgba(0,234,255,0.35),
    0 0 10px rgba(173,51,255,0.25),
    0 0 14px rgba(255,148,77,0.15);
  z-index:-2;
}

/* Mask inside so content stays clean */
.profile-card::after {
  content:"";
  position:absolute;
  inset:0;
  background: var(--card);
  border-radius:20px;
  z-index:-1;
}

@keyframes rainbowBorder {
  0% { background-position:0% 50%; }
  50% { background-position:100% 50%; }
  100% { background-position:0% 50%; }
}

.profile-header { display:flex; align-items:center; gap:25px; width:100%; margin-bottom:25px; }
.avatar {
  width:120px; height:120px; border-radius:50%;
  background:rgba(255,255,255,0.05);
  display:flex; align-items:center; justify-content:center;
  font-size:2.5rem; color:#fff;
  border:2px solid rgba(255,255,255,0.08);
}
.profile-info { flex:1; }
.profile-info h2 { margin:0; font-size:1.8rem; }
.profile-info p { margin:5px 0 0 0; color:var(--muted); font-size:0.95rem; }

/* Info list */
.info-list { width:100%; margin-top:20px; display:grid; grid-template-columns:1fr 1fr; gap:15px; }
.info-item {
  background: rgba(255,255,255,0.03);
  padding:12px 15px; border-radius:12px;
  display:flex; flex-direction:column;
  border:1px solid rgba(255,255,255,0.05);
}
.info-item label { font-size:0.85rem; color:var(--muted); margin-bottom:5px; }
.info-item span { font-weight:500; color:#fff; }

/* Change Password Link */
.change-pass-link { margin-top:10px; text-align:right; }
.change-pass-link a {
  text-decoration:none;
  font-size:0.9rem;
  color:var(--neon);
  font-weight:600;
}
.change-pass-link a:hover { text-decoration:underline; }

/* Logout Button Below Card */
.logout-btn { margin-top:40px; text-align:center; }
.logout-btn a {
  text-decoration:none;
  padding:12px 25px;
  border-radius:12px;
  background:transparent;
  border:1px solid rgba(255,255,255,0.06);
  color:red;
  font-weight:600;
  transition:0.3s;
}
.logout-btn a:hover { 
  background: red; 
  color:black; 
  border-color:red; 
}

/* Responsive */
@media(max-width:600px){
  .profile-header { flex-direction:column; align-items:center; text-align:center; }
  .info-list { grid-template-columns:1fr; }
}
footer { margin-top:36px; padding:28px 18px; text-align:center; color:var(--muted); font-size:0.9rem; }
</style>
</head>
<body>

<!-- HEADER & NAV -->
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
  <div class="profile-card">
    <!-- Header with avatar -->
    <div class="profile-header">
      <div class="avatar"><?php echo strtoupper($user['firstName'][0]); ?></div>
      <div class="profile-info">
        <h2><?php echo htmlspecialchars($user['firstName'] . " " . $user['lastName']); ?></h2>
        <p><?php echo htmlspecialchars($user['email']); ?></p>
      </div>
    </div>

    <!-- Info grid -->
    <div class="info-list">
      <div class="info-item">
        <label>First Name</label>
        <span><?php echo htmlspecialchars($user['firstName']); ?></span>
      </div>
      <div class="info-item">
        <label>Last Name</label>
        <span><?php echo htmlspecialchars($user['lastName']); ?></span>
      </div>
      <div class="info-item">
        <label>Email</label>
        <span><?php echo htmlspecialchars($user['email']); ?></span>
      </div>
      <div class="info-item">
        <label>Password</label>
        <span>********</span>
        <div class="change-pass-link">
          <a href="change_password.php">Change Password</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Logout Button Below Card -->
  <div class="logout-btn">
      <a href="../project-folder/logout.php">Logout</a>
    </div>
<div class="logout-btn" style="margin-top:60px;">
    <a href="delete_account.php" 
       onclick="return confirm('⚠️ Are you sure you want to delete your account? This action cannot be undone.');">
        Delete Account
    </a>
</div>
</div>

<footer>
  &copy; 2025 Scripting Hub. All Rights Reserved.
</footer>

</body>
</html>
