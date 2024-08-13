<?php
include("../conn/conn.php");
use PHPMailer\PHPMailer\PHPMailer;

require '../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['student_name'], $_POST['course_section'], $_POST['course_email'], $_POST['generated_code'])) {
        $studentName = $_POST['student_name'];
        $studentCourse = $_POST['course_section'];
        $studentEmail = $_POST['course_email'];
        $generatedCode = $_POST['generated_code'];

        try {
            // Insert student data into the database
            $stmt = $conn->prepare("INSERT INTO tbl_student (student_name, course_section, course_email, generated_code) VALUES (:student_name, :course_section, :course_email, :generated_code)");
            
            $stmt->bindParam(":student_name", $studentName, PDO::PARAM_STR); 
            $stmt->bindParam(":course_section", $studentCourse, PDO::PARAM_STR);
            $stmt->bindParam(":course_email", $studentEmail, PDO::PARAM_STR);
            $stmt->bindParam(":generated_code", $generatedCode, PDO::PARAM_STR);

            $stmt->execute();

            // Send the QR code to the student's email
             // Send the QR code to the student's email
            $mail = new PHPMailer(true); // Enable exceptions
            $mail->isSMTP();
            $mail->Host = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USER'];
            $mail->Password = $_ENV['SMTP_PASS'];
            $mail->SMTPSecure = 'tls';
            $mail->Port = $_ENV['SMTP_PORT'];

            $mail->setFrom($_ENV['SMTP_USER'], 'Attendance System');
            $mail->addAddress($studentEmail); // Add a recipient

            $mail->isHTML(true); // Set email format to HTML

            $mail->Subject = 'Your QR Code';
            $mail->Body    = 'Dear ' . $studentName . ',<br><br>Here is your QR code:<br><img src="cid:qrcode"><br><br>Thank you!';
            $mail->AltBody = 'Dear ' . $studentName . ', Here is your QR code. Thank you!';

             // Generate and attach the QR code image
            $qrData = $generatedCode;
            $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrData);
            $qrImageData = file_get_contents($qrCodeUrl);

            // Save the QR code image
            $qrCodePath = '../qrcode/qr.png';
            file_put_contents($qrCodePath, $qrImageData);

            // Embed the QR code in the email
            $mail->addEmbeddedImage($qrCodePath, 'qrcode', 'qr.png', 'base64', 'image/png');

            if (!$mail->send()) {
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                header("Location: ../masterlist.php");
                exit();
            }
        } catch (PDOException $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }

    } else {
        echo "
            <script>
                alert('Please fill in all fields!');
               window.location.href = '../masterlist.php';
            </script>
        ";
    }
}
?>
