<?php
session_start();

$error = '';
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username === 'admin' && $password === '12345') {
        $_SESSION['is_admin'] = true;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "<div class='alert alert-danger'>بيانات الدخول غير صحيحة. استخدم: admin / 12345</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل دخول الإدارة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="text-center mb-4 text-primary">تسجيل دخول المدير</h3>
                    <?= $error ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label>اسم المستخدم (admin)</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>كلمة المرور (12345)</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary w-100">دخول لـ لوحة التحكم</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>