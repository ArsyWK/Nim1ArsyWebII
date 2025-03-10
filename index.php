<?php
require 'database.php';
$db = new Database();
$pdo = $db->db; 


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $nim = $_POST['nim'];
    $major = $_POST['major'];
    $courses = $_POST['courses']; 

    $stmt = $pdo->prepare("INSERT INTO students (name, nim, major) VALUES (?, ?, ?)");
    $stmt->execute([$name, $nim, $major]);
    $student_id = $pdo->lastInsertId();


    foreach ($courses as $course_id) {
        $stmt = $pdo->prepare("INSERT INTO student_course (student_id, course_id) VALUES (?, ?)");
        $stmt->execute([$student_id, $course_id]);
    }

    header("Location: index.php");
    exit();
}

if (isset($_GET['delete'])) {
    $student_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
    $stmt->execute([$student_id]);

    header("Location: index.php");
    exit();
}


$stmt = $pdo->query("
    SELECT students.id, students.name, students.nim, students.major, 
           GROUP_CONCAT(courses.course_name SEPARATOR ', ') AS courses
    FROM students
    LEFT JOIN student_course ON students.id = student_course.student_id
    LEFT JOIN courses ON student_course.course_id = courses.id
    GROUP BY students.id
");
$students = $stmt->fetchAll();

$courses_stmt = $pdo->query("SELECT * FROM courses");
$courses = $courses_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Mahasiswa & Mata Kuliah</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Manajemen Mahasiswa & Mata Kuliah</h2>

       
        <form action="" method="POST" class="mb-3">
            <div class="mb-2">
                <label for="name" class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-2">
                <label for="nim" class="form-label">NIM</label>
                <input type="text" name="nim" class="form-control" required>
            </div>
            <div class="mb-2">
                <label for="major" class="form-label">Jurusan</label>
                <input type="text" name="major" class="form-control" required>
            </div>
            <div class="mb-2">
                <label for="courses" class="form-label">Mata Kuliah</label>
                <select name="courses[]" class="form-control" multiple required>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?= $course['id']; ?>"><?= $course['course_name']; ?> (<?= $course['credits']; ?> SKS)</option>
                    <?php endforeach; ?>
                </select>
                <small>Pilih lebih dari satu dengan Ctrl / Cmd</small>
            </div>
            <button type="submit" class="btn btn-primary">Tambah Mahasiswa</button>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Jurusan</th>
                    <th>Mata Kuliah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['id']); ?></td>
                        <td><?= htmlspecialchars($student['name']); ?></td>
                        <td><?= htmlspecialchars($student['nim']); ?></td>
                        <td><?= htmlspecialchars($student['major']); ?></td>
                        <td><?= htmlspecialchars($student['courses']) ?: 'Belum mengambil kursus'; ?></td>
                        <td>
                            <a href="?delete=<?= $student['id']; ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin ingin menghapus mahasiswa ini?')">
                                Hapus
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
