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

    // 데이터베이스에서 사용자 정보 확인
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $encrypted_password = $user['encrypted_password'];

        // 사용자가 제출한 비밀번호를 해싱 (SHA-256)
        $submitted_password = hash('sha256', $password);

        // 비밀번호 검증
        if ($encrypted_password === encryptAES($submitted_password, $encryption_key)) {
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

// AES 암호화 함수
function encryptAES($data, $key) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}
?>

