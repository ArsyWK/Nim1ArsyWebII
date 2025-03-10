<?php
require 'CourseManager.php';

$courseManager = new CourseManager();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $course_name = $_POST['course_name'];
    $credits = $_POST['credits'];
    $courseManager->addCourse($course_name, $credits);
    
}


if (isset($_GET['delete'])) {
    $courseManager->deleteCourse($_GET['delete']);
    header("Location: courses.php");
    exit();
}


$courses = $courseManager->getCourses();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Mata Kuliah</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Manajemen Mata Kuliah</h2>
        <form action="" method="POST" class="mb-3">
            <div class="mb-2">
                <label for="course_name" class="form-label">Nama Mata Kuliah</label>
                <input type="text" name="course_name" class="form-control" required>
            </div>
            <div class="mb-2">
                <label for="credits" class="form-label">SKS</label>
                <input type="number" name="credits" class="form-control" required>
            </div>
            <button type="submit" name="add" class="btn btn-primary">Tambah Mata Kuliah</button>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Mata Kuliah</th>
                    <th>SKS</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td><?= $course['id']; ?></td>
                        <td><?= $course['course_name']; ?></td>
                        <td><?= $course['credits']; ?></td>
                        <td>
                            <a href="courses.php?delete=<?= $course['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
