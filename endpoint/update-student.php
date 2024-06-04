<?php
include("../conn/conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['student_name'], $_POST['course_section'], $_POST['tbl_student_id'], $_POST['course_email'])) {
        $studentId = $_POST['tbl_student_id'];
        $studentName = $_POST['student_name'];
        $studentCourse = $_POST['course_section'];
        $studentEmail = $_POST['course_email'];

        // Assuming $conn is already defined and connected to the database
        try {
            $stmt = $conn->prepare("UPDATE tbl_student SET student_name = :student_name, course_section = :course_section, course_email = :course_email WHERE tbl_student_id = :tbl_student_id");
            
            $stmt->bindParam(":tbl_student_id", $studentId, PDO::PARAM_STR); 
            $stmt->bindParam(":student_name", $studentName, PDO::PARAM_STR); 
            $stmt->bindParam(":course_section", $studentCourse, PDO::PARAM_STR);
            $stmt->bindParam(":course_email", $studentEmail, PDO::PARAM_STR);

            $stmt->execute();

            header("Location: ./masterlist.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }

    } else {
        echo "<script>
                alert('Please fill in all fields!');
                window.location.href = 'http://localhost/qr-code-attendance-system/masterlist.php';
              </script>";
    }
}
?>
