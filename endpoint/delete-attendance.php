<?php
include ('../conn/conn.php');

if (isset($_GET['attendance'])) {
    $attendance = $_GET['attendance'];

    try {
        // Prepare the SQL statement with a placeholder to prevent SQL injection
        $query = "DELETE FROM tbl_attendance WHERE tbl_attendance_id = :attendance";
        $stmt = $conn->prepare($query);

        // Bind the attendance ID to the placeholder
        $stmt->bindParam(':attendance', $attendance, PDO::PARAM_INT);

        // Execute the query
        $query_execute = $stmt->execute();

        // Check if the query executed successfully
        if ($query_execute) {
            // Redirect to index.php if successful
            header("Location: ../index.php");
            exit();
        } else {
            // Handle case where deletion fails
            echo "
                <script>
                    alert('Failed to delete attendance!');
                    window.location.href = '../index.php';
                </script>
            ";
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
