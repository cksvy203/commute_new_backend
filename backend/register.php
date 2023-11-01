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
    $password = $_POST['password'];
    $username = $_POST['username'];

    // 암호화된 비밀번호 생성
    $password_salt = bin2hex(random_bytes(32)); // 새로운 솔트 생성
    $password_hash = hash('sha256', $password_salt . $password); // 비밀번호 해싱

    // 데이터베이스에 사용자 정보 삽입
    $sql = "INSERT INTO users (email, password_salt, password_hash) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password_salt, $password_hash);
    $stmt->execute();

    // 회원가입 성공 메시지 반환
    echo json_encode(array("message" => "회원가입이 완료되었습니다."));
    header("Location: ../main.html");
}
?>
