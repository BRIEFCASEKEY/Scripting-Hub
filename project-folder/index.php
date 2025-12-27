<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Scripting Hub — Register & Sign In</title>

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<style>
* { font-family: "Poppins", sans-serif; }

:root{
  --bg:#000;
  --card:#0f1113;
  --muted:#bfc7d1;
  --accent:#00bfff;
  --accent-hover:#00eaff;
}

body{
  margin:0;
  background:var(--bg);
  color:#fff;
}

header{
  padding:28px 18px;
  text-align:center;
}

.brand{
  font-size:1.6rem;
  font-weight:700;
  letter-spacing:1px;
}

.tag{
  font-weight:300;
  color:var(--muted);
  font-size:0.9rem;
  margin-top:6px;
}

main{
  display:flex;
  flex-direction:column;
  align-items:center;
  padding:40px 18px;
}

/* ===== CARD ===== */
.card{
  position: relative;
  background: var(--card);
  padding: 40px 30px;
  border-radius: 16px;
  max-width: 400px;
  width: 100%;
  margin-bottom: 20px;
  animation: floatIn 0.6s ease;
  z-index: 1;
}

/* 🌈 Gradient BORDER with subtle glow */
.card::before{
  content:"";
  position:absolute;
  inset:-2px;                     /* border thickness */
  border-radius:18px;
  background: linear-gradient(
    120deg,
    #00eaff,
    #ad33ff,
    #99ff99,
    #ff944d
  );
  background-size:300% 300%;
  animation: rainbowBorder 6s linear infinite;

  /* ✨ tiny glow (border only) */
  box-shadow:
    0 0 6px rgba(0,234,255,0.35),
    0 0 10px rgba(173,51,255,0.25),
    0 0 14px rgba(255,148,77,0.15);

  z-index:-2;
}

/* Mask inside so glow doesn't enter */
.card::after{
  content:"";
  position:absolute;
  inset:0;
  background:var(--card);
  border-radius:16px;
  z-index:-1;
}

@keyframes rainbowBorder{
  0%{background-position:0% 50%;}
  50%{background-position:100% 50%;}
  100%{background-position:0% 50%;}
}

@keyframes floatIn{
  from{opacity:0;transform:translateY(20px);}
  to{opacity:1;transform:translateY(0);}
}

.hidden{display:none;}

.card h2{
  text-align:center;
  margin-bottom:24px;
  color:white;
}

.form-group{
  margin-bottom:18px;
  display:flex;
  flex-direction:column;
}

label{
  margin-bottom:6px;
  color:var(--muted);
}

input{
  padding:10px 12px;
  border-radius:8px;
  border:1px solid rgba(255,255,255,0.1);
  background:transparent;
  color:#fff;
  font-size:1rem;
}

input::placeholder{
  color:var(--muted);
}

input:focus{
  outline:none;
  border-color:var(--accent);
  box-shadow:0 0 10px var(--accent);
}

.btn{
  width:100%;
  padding:12px;
  border-radius:10px;
  border:1px solid rgba(255,255,255,0.06);
  background:transparent;
  color:white;
  font-weight:600;
  cursor:pointer;
  transition:0.3s;
  margin-top:10px;
}

.btn:hover{
  background:white;
  color:black;
  border-color:white;
}

.switch-text{
  text-align:center;
  margin-top:18px;
  font-size:0.9rem;
  color:var(--muted);
}

.switch-text button{
  background:none;
  border:none;
  color:var(--accent);
  font-weight:600;
  cursor:pointer;
  margin-left:5px;
}

.switch-text button:hover{
  color:var(--accent-hover);
}

.message{
  text-align:center;
  margin-bottom:20px;
  font-weight:600;
}
.password-wrap {
  position: relative;      /* wrapper must be relative */
  display: inline-block;   /* ensures proper sizing */
  width: 100%;
}

.password-wrap input {
  width: 100%;
  padding-right: 40px;     /* space for the eye */
  box-sizing: border-box;  /* include padding in width */
}

.password-wrap .eye-btn {
  position: absolute;      /* put inside input */
  right: 10px;             /* distance from right edge */
  top: 50%;                
  transform: translateY(-50%); /* center vertically */
  background: transparent;
  border: none;
  cursor: pointer;
  font-size: 1.1rem;
  color: var(--muted);
  padding: 0;
  line-height: 1;
}

.password-wrap .eye-btn:hover {
  color: white;
}

.password-wrap .eye-btn:focus {
  outline: none;
}

footer{
  text-align:center;
  padding:28px 18px;
  color:var(--muted);
  font-size:0.9rem;
}
</style>
</head>

<body>

<header>
  <div class="brand">Scripting Hub</div>
  <div class="tag">Interactive lessons • Practice playground • Beginner-friendly</div>
</header>

<main>
<?php
if(isset($_GET['registered'])){
  echo '<p class="message" style="color:green;">Registration successful! Please sign in below.</p>';
}
if(isset($_GET['error'])){
  echo '<p class="message" style="color:red;">';
  if($_GET['error']=="incorrect") echo "Incorrect password.";
  if($_GET['error']=="notfound") echo "No user found with this email.";
  if($_GET['error']=="exists") echo "Email already registered.";
  echo '</p>';
}
?>

<div class="card" id="loginCard">
  <h2>Sign In</h2>
  <form action="login.php" method="POST">
    <div class="form-group">
      <label>Email Address</label>
      <input type="email" name="email" placeholder="example@email.com" required>
    </div>
    <div class="form-group">
  <label>Password</label>
  <div class="password-wrap">
    <input type="password" id="loginPassword" name="password" placeholder="Enter your password" required>
    <button type="button" class="eye-btn" onclick="togglePassword('loginPassword', this)">👁</button>
  </div>
</div>
    <button class="btn" type="submit">Login</button>
  </form>
  <div class="switch-text">
    Don’t have an account?
    <button type="button" onclick="showRegister()">Register</button>
  </div>
</div>

<div class="card hidden" id="registerCard">
  <h2>Create Account</h2>
  <form action="register.php" method="POST">
    <div class="form-group">
      <label>First Name</label>
      <input type="text" name="firstName" placeholder="John" required>
    </div>
    <div class="form-group">
      <label>Last Name</label>
      <input type="text" name="lastName" placeholder="Doe" required>
    </div>
    <div class="form-group">
      <label>Email Address</label>
      <input type="email" name="email" placeholder="example@email.com" required>
    </div>
    <div class="form-group">
  <label>Password</label>
  <div class="password-wrap">
    <input type="password" id="registerPassword" name="password" placeholder="Create a password" required>
    <button type="button" class="eye-btn" onclick="togglePassword('registerPassword', this)">👁</button>
  </div>
</div>
    <button class="btn" type="submit">Register</button>
  </form>
  <div class="switch-text">
    Already have an account?
    <button type="button" onclick="showLogin()">Sign In</button>
  </div>
</div>

</main>

<footer>&copy; 2025 Scripting Hub. All rights reserved.</footer>
<script src="script.js"></script>
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
