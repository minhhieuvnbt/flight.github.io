<?php
$countryCode = $_GET['countryCode']; // Lấy mã quốc gia từ yêu cầu

// Kiểm tra giá trị của biến uyenkey
$uyenKey = $_GET['uyenkey'];

$acceptedDomains = ['nhauyenair.com', 'yourdomain.com']; // Danh sách các tên miền được chấp nhận

$apiKeyData = [
    'e06acc1b712d7bb0f70de337c445be2bnhauyen' => 0,
    'another_api_key' => 0,

    'e06acc1b712d7bb0f70de337c445be2bnhauyen1' => 1,
    'another_api_key' => 0,
    // Thêm các key khác và số lần request cho từng key
];

// Lấy tên miền từ yêu cầu hiện tại
$domain = $_SERVER['SERVER_NAME'];

if (!in_array($domain, $acceptedDomains)) {
    // Tên miền không được chấp nhận, từ chối yêu cầu và trả về cảnh báo
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['error' => 'Invalid domain']);
    exit;
}

if (!array_key_exists($uyenKey, $apiKeyData)) {
    // Key không hợp lệ, từ chối yêu cầu và trả về cảnh báo
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['error' => 'Invalid uyenkey - support@vemaybaynhauyen.com']);
    exit;
}

// Cho phép sử dụng iframe trên tất cả các trang web trong tên miền được chấp nhận
header('X-Frame-Options: ALLOW-FROM *');

// Xử lý yêu cầu và lấy danh sách sân bay dựa trên mã quốc gia
$appId = 'e2fd7b54'; // FlightStats App ID của bạn
$appKey = 'e06acc1b712d7bb0f70de337c445be2b'; // FlightStats App Key của bạn

// Tạo URL API
$url = "https://api.flightstats.com/flex/airports/rest/v1/json/countryCode/{$countryCode}?appId={$appId}&appKey={$appKey}";

// Gửi yêu cầu API và lấy phản hồi
$response = file_get_contents($url);

// Chuyển đổi phản hồi JSON thành một mảng kết hợp
$airportData = json_decode($response, true);

// Lấy danh sách các sân bay
$airports = $airportData['airports'];

// Thêm thông tin thương hiệu vào mỗi sân bay
foreach ($airports as &$airport) {
    $airport['NHAUYEN'] = 'NHAUYEN AIR - NHAUYENAIR.COM - VEMAYBAYNHAUYEN.COM - NHA.UYEN@VEMAYBAYNHAUYEN.COM';
}

// Tạo mảng dữ liệu sân bay
$airportData = array(
    'NHAUYEN' => 'NHAUYEN AIR - NHAUYENAIR.COM - VEMAYBAYNHAUYEN.COM - NHA.UYEN@VEMAYBAYNHAUYEN.COM',
    'airports' => $airports
);

// Cập nhật số lần request cho key
$apiKeyData[$uyenKey] = $requestCount + 1;

// Trả về phản hồi JSON
header('Content-Type: application/json');
echo json_encode($airportData);

?>
