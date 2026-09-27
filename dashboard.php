<?php
session_start();
require 'db.php';

if (!isset($_SESSION['is_admin'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM employees WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: dashboard.php");
    exit();
}

if (isset($_POST['add_emp'])) {
    $stmt = $pdo->prepare("INSERT INTO employees (name, email, position, salary) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['name'], $_POST['email'], $_POST['position'], $_POST['salary']]);
    header("Location: dashboard.php");
    exit();
}

if (isset($_POST['edit_emp'])) {
    $stmt = $pdo->prepare("UPDATE employees SET name=?, email=?, position=?, salary=? WHERE id=?");
    $stmt->execute([$_POST['name'], $_POST['email'], $_POST['position'], $_POST['salary'], $_POST['id']]);
    header("Location: dashboard.php");
    exit();
}

$emp_to_edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $emp_to_edit = $stmt->fetch();
}

$employees = $pdo->query("SELECT * FROM employees ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدارة الموظفين</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">نظام إدارة الموظفين (CRUD)</h2>
        <a href="?logout=true" class="btn btn-danger">تسجيل الخروج</a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title"><?= $emp_to_edit ? 'تعديل بيانات الموظف' : 'إضافة موظف جديد' ?></h5>
                    <form method="POST">
                        <?php if ($emp_to_edit): ?>
                            <input type="hidden" name="id" value="<?= $emp_to_edit['id'] ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label>الاسم الكامل</label>
                            <input type="text" name="name" class="form-control" value="<?= $emp_to_edit['name'] ?? '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control" value="<?= $emp_to_edit['email'] ?? '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>المسمى الوظيفي</label>
                            <input type="text" name="position" class="form-control" value="<?= $emp_to_edit['position'] ?? '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>الراتب</label>
                            <input type="number" step="0.01" name="salary" class="form-control" value="<?= $emp_to_edit['salary'] ?? '' ?>" required>
                        </div>
                        
                        <?php if ($emp_to_edit): ?>
                            <button type="submit" name="edit_emp" class="btn btn-warning w-100 mb-2">تحديث البيانات</button>
                            <a href="dashboard.php" class="btn btn-secondary w-100">إلغاء</a>
                        <?php else: ?>
                            <button type="submit" name="add_emp" class="btn btn-success w-100">إضافة الموظف</button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- جدول عرض الموظفين -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">قائمة الموظفين</h5>
                    <table class="table table-bordered table-striped mt-3 text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>م</th>
                                <th>الاسم</th>
                                <th>البريد</th>
                                <th>الوظيفة</th>
                                <th>الراتب</th>
                                <th>إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($employees as $index => $emp): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($emp['name']) ?></td>
                                <td><?= htmlspecialchars($emp['email']) ?></td>
                                <td><?= htmlspecialchars($emp['position']) ?></td>
                                <td><?= number_format($emp['salary'], 2) ?>$</td>
                                <td>
                                    <a href="?edit=<?= $emp['id'] ?>" class="btn btn-sm btn-primary">تعديل</a>
                                    <a href="?delete=<?= $emp['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>