<?php
include("../conn/conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['student_name'], $_POST['course_section'], $_POST['course_email'], $_POST['generated_code'])) {
        $studentName = $_POST['student_name'];
        $studentCourse = $_POST['course_section'];
        $studentEmail = $_POST['course_email'];
        $generatedCode = $_POST['generated_code'];

        try {
            $stmt = $conn->prepare("INSERT INTO tbl_student (student_name, course_section, course_email, generated_code) VALUES (:student_name, :course_section, :course_email, :generated_code)");
            
            $stmt->bindParam(":student_name", $studentName, PDO::PARAM_STR); 
            $stmt->bindParam(":course_section", $studentCourse, PDO::PARAM_STR);
            $stmt->bindParam(":course_email", $studentEmail, PDO::PARAM_STR);
            $stmt->bindParam(":generated_code", $generatedCode, PDO::PARAM_STR);

            $stmt->execute();

            header("Location: http://localhost/qr-code-attendance-system/masterlist.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }

    } else {
        echo "
            <script>
                alert('Please fill in all fields!');
                window.location.href = 'http://localhost/qr-code-attendance-system/masterlist.php';
            </script>
        ";
    }
}
?>
