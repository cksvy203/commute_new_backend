<?php
// 데이터베이스 연동 부분
$servername = "192.168.10.73"; // DB 서버 주소
$username = "cksvy203"; // DB 서버 ID
$password = "cksvy4964"; // DB 서버 PW
$dbname = "app_login"; // DB명

// 데이터베이스 서버 연결 변수
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("데이터베이스 연동 실패: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password_salt = $_POST['password_salt'];

    // 비밀번호 해싱 및 솔트 검색
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $stored_password_salt = $user['password_salt'];

        // 사용자가 제출한 비밀번호를 검증
        if (password_verify($password_salt, $stored_password_salt)) {
            // 로그인 성공
            header("Location: ../main.html");
        } else {
            // 로그인 실패
            $status = "Incorrect";
        }
    } else {
        // 사용자가 존재하지 않음
        $status = "User not found";
    }
}
?>