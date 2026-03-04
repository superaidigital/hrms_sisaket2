<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - HRMS Sisaket PAO</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background: linear-gradient(135deg, #0f172a, #1e293b); /* พื้นหลังสีเข้ม Slate */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            display: flex;
        }
        .login-left {
            background: linear-gradient(135deg, #0f766e, #14b8a6); /* Teal Gradient */
            padding: 4rem;
            color: white;
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-right {
            padding: 4rem;
            width: 50%;
            background: #ffffff;
        }
        .form-control {
            padding: 0.8rem 1rem;
            border-radius: 12px;
            background-color: #f8fafc;
        }
        .btn-login {
            background: linear-gradient(to right, #14b8a6, #10b981);
            color: white;
            padding: 0.8rem;
            border-radius: 12px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(20, 184, 166, 0.4);
            color: white;
        }
        /* สำหรับหน้าจอมือถือ */
        @media (max-width: 768px) {
            .login-card { flex-direction: column; max-width: 400px; margin: 20px; }
            .login-left { width: 100%; padding: 2rem; text-align: center; }
            .login-right { width: 100%; padding: 2rem; }
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-left">
        <div class="mb-4">
            <i class="fa-solid fa-users fs-1 mb-3 bg-white text-teal p-3 rounded-circle shadow-sm" style="color: #0d9488;"></i>
        </div>
        <h1 class="fw-bolder mb-2">HRMS <span style="color: #ccfbf1;">Sisaket</span></h1>
        <p class="fs-5 opacity-75">ระบบจัดการทรัพยากรบุคคล องค์การบริหารส่วนจังหวัดศรีสะเกษ</p>
        <div class="mt-auto">
            <small class="opacity-50">&copy; 2026 Sisaket PAO. All rights reserved.</small>
        </div>
    </div>
    
    <div class="login-right">
        <h3 class="fw-bold text-dark mb-4">เข้าสู่ระบบ</h3>
        
        <?php if(isset($_SESSION['login_error'])): ?>
            <div class="alert alert-danger rounded-3 small fw-bold">
                <i class="fa-solid fa-circle-exclamation me-1"></i> <?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['message'])): ?>
            <div class="alert alert-success rounded-3 small fw-bold">
                <i class="fa-solid fa-circle-check me-1"></i> <?php echo $_SESSION['message']; unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
            </div>
        <?php endif; ?>

        <form action="index.php?action=login" method="POST">
            <div class="mb-3">
                <label class="form-label text-muted small fw-bold text-uppercase">ชื่อผู้ใช้งาน (Username)</label>
                <input type="text" name="username" class="form-control" required autofocus placeholder="กรอกชื่อผู้ใช้งาน">
            </div>
            <div class="mb-4">
                <label class="form-label text-muted small fw-bold text-uppercase">รหัสผ่าน (Password)</label>
                <input type="password" name="password" class="form-control" required placeholder="กรอกรหัสผ่าน">
            </div>
            <button type="submit" class="btn btn-login border-0">
                เข้าสู่ระบบ <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i>
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>