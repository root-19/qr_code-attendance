<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include("../conn/conn.php");

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['qr_code'])) {
        $qrCode = $_POST['qr_code'];

        $selectStmt = $conn->prepare("SELECT tbl_student_id, student_name, course_email FROM tbl_student WHERE generated_code = :generated_code");
        $selectStmt->bindParam(":generated_code", $qrCode, PDO::PARAM_STR);

        if ($selectStmt->execute()) {
            $result = $selectStmt->fetch();

            if ($result !== false) {
                $studentID = $result["tbl_student_id"];
                $studentName = $result["student_name"];
                $studentEmail = $result["course_email"];
                $timeIn = date("Y-m-d H:i:s");

                try {
                    // Insert attendance record
                    $stmt = $conn->prepare("INSERT INTO tbl_attendance (tbl_student_id, time_in) VALUES (:tbl_student_id, :time_in)");
                    $stmt->bindParam(":tbl_student_id", $studentID, PDO::PARAM_STR);
                    $stmt->bindParam(":time_in", $timeIn, PDO::PARAM_STR);
                    $stmt->execute();

                    // Send email notification
                    $mail = new PHPMailer(true);
                    try {
                        //Server settings
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'wasieacuna@gmail.com'; 
                        $mail->Password = 'qipc vais smfq rwim'; 
                        $mail->SMTPSecure = 'ssl'; 
                        $mail->Port = 465;

                        //Recipients
                        $mail->setFrom('wasieacuna@gmail.com', 'Attendance System');
                        $mail->addAddress($studentEmail, $studentName);

                        // Content
                        $mail->isHTML(true);
                        $mail->Subject = 'Attendance Recorded';
                       $mail->Body = "Hi $studentName,<br><br>We are pleased to inform you that your attendance has been successfully recorded at $timeIn.<br><br>Best regards,<br>Attendance System";


                        $mail->send();
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }

                    header("Location: ../index.php");
                    exit();
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            } else {
                echo "No student found for the provided QR code.";
            }
        } else {
            echo "Failed to execute the statement.";
        }
    } else {
        echo "<script>
                alert('Please fill in all fields!');
                window.location.href = '../index.php';
              </script>";
    }
}
?>
