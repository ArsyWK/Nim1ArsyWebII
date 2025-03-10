    <?php
    require 'Database.php';

    class CourseManager {
        private $db;

        public function __construct() {
            $database = new Database();
            $this->db = $database->db;
        }

    
        public function addCourse($course_name, $credits) {
            $stmt = $this->db->prepare("INSERT INTO courses (course_name, credits) VALUES (?, ?)");
            return $stmt->execute([$course_name, $credits]);
        }

    
        public function getCourses() {
            $stmt = $this->db->query("SELECT * FROM courses");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        
        public function getCourseById($id) {
            $stmt = $this->db->prepare("SELECT * FROM courses WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        
        public function updateCourse($id, $course_name, $credits) {
            $stmt = $this->db->prepare("UPDATE courses SET course_name = ?, credits = ? WHERE id = ?");
            return $stmt->execute([$course_name, $credits, $id]);
        }

    
        public function deleteCourse($id) {
            $stmt = $this->db->prepare("DELETE FROM courses WHERE id = ?");
            return $stmt->execute([$id]);
        }
    }
    ?>
