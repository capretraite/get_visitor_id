<?php

header("Access-Control-Allow-Origin: *");
header('Content-type: application/javascript');

$now = new DateTime();
$expire = $now->modify('+ 1 month');

//$visitor_id = $this->Postform_model->getUuid();
//$visitor_id = $visitor_id['uuid'];
$visitor_id = uniqid();

$referer = $_SERVER['HTTP_REFERER'];
$referer = str_replace($_SERVER['REQUEST_SCHEME'].'://', '', $referer);
list($domain) = explode('/', $referer); 
$domain = explode('.', $domain);
$domain_site = $domain[count($domain)-2].'.'.$domain[count($domain)-1];

$domain_ct = explode('.', $_SERVER['HTTP_HOST']);
$domain_ct = $domain_ct[count($domain_ct)-2].'.'.$domain_ct[count($domain_ct)-1]; 

if(isset($_COOKIE['visitor_id'])){
    $visitor_id = $_COOKIE['visitor_id'];
    $response = json_encode($_COOKIE);
}
else{
    $created = setcookie("visitor_id", $visitor_id, $expire->getTimestamp(), '/', $domain_ct);
    $response = json_encode(array('visitor_id' => $visitor_id, 'created' => $created, 'cookie'=> $_COOKIE));
}
?>
var visitor_data = <?=$response;?>;
dataLayer.push({'visitor_id':visitor_data.visitor_id});

function setCookie(cname, cvalue, exdays, domain) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/; domain=" + domain + ";";
}

setCookie('visitor_id', '<?=$visitor_id?>', 30, '<?=$domain_site?>');