<?php
require_once 'crest/src/crest.php';
define('B24_WEBHOOK','https://b24-kvf3tn.bitrix24.ua/rest/1/c1jl5wgwj1wbdcli/');
echo "<pre>";
function BX($method, $var){
    $queryUrl = B24_WEBHOOK.$method.'.json';
    $queryData = http_build_query($var);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $queryUrl,
        CURLOPT_POSTFIELDS => $queryData,
    ));
    return json_decode(curl_exec($curl), true);
}

function sendMessage($chatID, $messaggio, $token) {
    echo "sending message to " . $chatID . "\n";

    $url = "https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chatID;
    $url = $url . "&text=" . urlencode($messaggio);
    $ch = curl_init();
    $optArray = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true
    );
    curl_setopt_array($ch, $optArray);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
$LEAD_ID =  $_REQUEST['data']['FIELDS']['ID'];
$id      = BX ('crm.lead.get', ['ID' => $LEAD_ID]);
print_r($id);

$x=CRest::call('crm.lead.add', [
    'fields' => [
        'TITLE' => $id['result']['TITLE'],
        'UF_CRM_1603196076929' => $id['result']['UF_CRM_1603196651096'],
    ],
    'params' => ['REGISTER_SONET_EVENT'=> 'Y']
]);
sendMessage(318942224,
    json_encode($id['result']['UF_CRM_1603196651096']),
    "860948571:AAGHoIqIHlHt8UVpa87UZJPnAlBC6JGAoc4");
