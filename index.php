<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Attendance System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
       <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(to bottom, rgba(255,255,255,0.15) 0%, rgba(0,0,0,0.15) 100%), radial-gradient(at top center, rgba(255,255,255,0.40) 0%, rgba(0,0,0,0.40) 120%) #989898;
            background-blend-mode: multiply, multiply;
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .attendance-container {
            width: 100%;
            max-width: 1200px;
            border-radius: 20px;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        }

        .scanner-con {
            border: 2px solid #ddd;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            background-color: #f9f9f9;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            margin-top:45px;
        }

        .viewport {
            min-height: 300px;
            max-height: 300px;
        }

        .table-container {
            max-height: 450px;
            overflow-y: auto;
            display: block;
            margin-top: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            min-width: 650px;
        }

        thead th {
            position: sticky;
            top: 0;
            background-color: #333;
            color: white;
            z-index: 2;
        }

        tbody tr {
            border-bottom: 1px solid #ddd;
        }

        td, th {
            padding: 12px;
            text-align: left;
        }

        .text {
            color: #f8fafc;
            font-size: 1.25rem;
            font-weight: bold;
        }

        .btn-danger {
            background-color: red;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .btn-danger:hover {
            background-color: darkred;
        }

        nav {
            background-color: #020617;
            padding: 15px;
        }

        .navbar-toggler-icon {
            display: inline-block;
            width: 30px;
            height: 2px;
            background-color: white;
            margin-bottom: 6px;
        }

        .navbar-toggler-icon:last-child {
            margin-bottom: 0;
        }

        @media (max-width: 768px) {
            .attendance-container {
                padding: 30px;
            }

            .scanner-con {
                max-width: 100%;
            }

            .table-container {
                max-height: 300px;
            }
        }
    </style>
</head>
<body>
    <nav class="p-4 flex items-center justify-between">
        <p class="text">QR Code Attendance System</p>
        <button class="text-white md:hidden" type="button" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" onclick="toggleNavbar()">
            <div class="navbar-toggler-icon"></div>
            <div class="navbar-toggler-icon"></div>
            <div class="navbar-toggler-icon"></div>
        </button>
        <div class="hidden md:flex" id="navbarSupportedContent">
            <ul class="flex space-x-4">
                <li class="nav-item active">
                    <a class="nav-link text-white" href="./index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="./masterlist.php">List of Students</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="./timeout.php">Time Out</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="main">
        <div class="attendance-container grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="qr-container">
                <div class="scanner-con">
                    <h5 class="text-center mb-4">Scan your QR Code or tap here for your attendance</h5>
                    <video id="interactive" class="viewport w-full"></video>
                </div>
                <div class="qr-detected-container hidden">
                    <form id="attendanceForm" action="./endpoint/add-attendance.php" method="POST">
                        <input type="hidden" id="detected-qr-code" name="qr_code">
                    </form>
                </div>
            </div>

            <div class="attendance-list">
                <h4 class="mb-4 text-xl font-bold">List of Present Students</h4>
                <div class="table-container">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b">No.</th>
                                <th class="py-2 px-4 border-b">Name</th>
                                <th class="py-2 px-4 border-b">Section</th>
                                <th class="py-2 px-4 border-b">Time In</th>
                                <th class="py-2 px-4 border-b">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            <?php 
                                include ('./conn/conn.php');
                                $stmt = $conn->prepare("SELECT * FROM tbl_attendance LEFT JOIN tbl_student ON tbl_student.tbl_student_id = tbl_attendance.tbl_student_id");
                                $stmt->execute();
                                $result = $stmt->fetchAll();
                                foreach ($result as $row) {
                                    $attendanceID = $row["tbl_attendance_id"];
                                    $studentName = $row["student_name"];
                                    $studentCourse = $row["course_section"];
                                    $timeIn = $row["time_in"];
                            ?>
                            <tr>
                                <td class="py-2 px-4 border-b"><?= $attendanceID ?></td>
                                <td class="py-2 px-4 border-b"><?= $studentName ?></td>
                                <td class="py-2 px-4 border-b"><?= $studentCourse ?></td>
                                <td class="py-2 px-4 border-b"><?= $timeIn ?></td>
                                <td class="py-2 px-4 border-b text-center">
                                    <button class="btn btn-danger" onclick="deleteAttendance(<?= $attendanceID ?>)">X</button>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        let scanner;

        function startScanner() {
            scanner = new Instascan.Scanner({ video: document.getElementById('interactive') });

            scanner.addListener('scan', function (content) {
                document.getElementById("detected-qr-code").value = content;
                document.getElementById("attendanceForm").submit();
                scanner.stop();
                document.querySelector(".qr-detected-container").style.display = 'block';
                document.querySelector(".scanner-con").style.display = 'none';
            });
       
               //for the scan
         $(document).ready(function() {
            let scanner = new Instascan.Scanner({ video: document.getElementById('interactive') });
            scanner.addListener('scan', function(content) {
                $('#detected-qr-code').val(content);
                Swal.fire({
                    icon: 'success',
                    title: 'QR Code Detected!',
                    text: 'Submitting your attendance...',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    $('#attendanceForm').submit();
                });
            });
            Instascan.Camera.getCameras().then(function(cameras) {
                if (cameras.length > 0) {
                    scanner.start(cameras[0]);
                } else {
                    console.error('No cameras found.');
                }
            }).catch(function(e) {
                console.error(e);
            });
        });
        }

        function toggleNavbar() {
            const navbarContent = document.getElementById("navbarSupportedContent");
            navbarContent.classList.toggle("hidden");
        }

        function deleteAttendance(id) {
            if (confirm("Are you sure you want to delete this record?")) {
                $.ajax({
                    url: "./endpoint/delete-attendance.php",
                    method: "POST",
                    data: { id: id },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted',
                            text: 'The attendance record has been deleted.',
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was a problem deleting the record.',
                        });
                    }
                });
            }
        }

        startScanner();
    </script>
</body>
</html>
