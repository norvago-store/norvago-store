<?php
function testGetUrl($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 6);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['code' => $code, 'body' => $res];
}

function testPostJson($url, $data) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 6);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['code' => $code, 'body' => $res];
}

// Let's test a real active Indonesian MLBB ID
// Example: 284897284 (2265) or 38241071 (2058) or other real user IDs
$id = '284897284';
$zone = '2265';

echo "Testing ID: $id ($zone)\n";

// Endpoint A: Api San / Isan / Alpha API
$r1 = testGetUrl("https://api.isan.eu.org/nickname/ml?id={$id}&zone={$zone}");
echo "A. isan: " . $r1['code'] . " -> " . $r1['body'] . "\n";

// Endpoint B: Gameloka / Bangjeff open inquiry
$r2 = testGetUrl("https://api-topup.gameloka.com/api/v1/order/check-id?game=mobile-legends&user_id={$id}&zone_id={$zone}");
echo "B. gameloka: " . $r2['code'] . " -> " . substr($r2['body'], 0, 150) . "\n";

// Endpoint C: Api raja topup / web api
$r3 = testGetUrl("https://api.zenzapis.xyz/api/checker/ml?id={$id}&zone={$zone}&apikey=zenzkey");
echo "C. zenz: " . $r3['code'] . " -> " . substr($r3['body'], 0, 150) . "\n";

// Endpoint D: Vocagame inquiry
$r4 = testPostJson("https://api.vocagame.com/v1/order/prepare", [
    'game_code' => 'mlbb',
    'user_id' => $id,
    'zone_id' => $zone,
]);
echo "D. vocagame: " . $r4['code'] . " -> " . substr($r4['body'], 0, 150) . "\n";

// Endpoint E: Web Codashop with session / headers
$ch = curl_init("https://order-sg.codashop.com/initPayment.action");
$profile = base64_encode(json_encode(['name' => 'demo', 'data' => ['user_id' => $id, 'zone_id' => $zone]]));
$fields = [
    'voucherPricePoint.id' => '1160',
    'voucherPricePoint.price' => '1500.0',
    'voucherPricePoint.variablePrice' => '0',
    'email' => '',
    'n' => date('d/m/Y-H:i:s'),
    'userVariablePrice' => '0',
    'order.data.profile' => $profile,
    'voucherTypeName' => 'MOBILE_LEGENDS',
    'shopLang' => 'id_ID',
];
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Origin: https://www.codashop.com',
    'Referer: https://www.codashop.com/id-id/mobile-legends',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    'Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 6);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$resE = curl_exec($ch);
$codeE = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "E. codashop with headers: " . $codeE . " -> " . substr($resE, 0, 250) . "\n";
