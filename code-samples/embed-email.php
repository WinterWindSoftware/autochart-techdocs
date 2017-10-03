<?php

function GetAutochartVisitorSummary($accountId, $apiReadKey)
{
    // Get visitorId from JSON within cookie
    $acVisitorCookie = $_COOKIE['ac_visitor']; //{%22id%22:%2259cd3ce65242b75baf000001%22}
    if (isset($acVisitorCookie)) {
        $visitorId = json_decode(urldecode($acVisitorCookie))->id;
        // Uses curl library to make a HTTP call to the Autochart API
        $curl = curl_init();
        $url = "https://portal.autochart.io/api/1/accounts/{$accountId}/visitors/{$visitorId}/text-summary";
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$apiReadKey}"));
        // Wait for maximum of 10 seconds
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($http_status !== 200) {
            return false;
        }
        curl_close($curl);
        return $result;
    }
    return false;
}

$autochartAccountId = '512345678901234567890123';  // TODO: read from config setting
$autochartApiReadKey = 'rk_a123456789abcdef0123456789abcdef'; // TODO: read from config setting
$autochartVisitorSummary = GetAutochartVisitorSummary($autochartAccountId, $autochartApiReadKey);

if ($autochartVisitorSummary === false) {
    $autochartVisitorSummary = 'Unable to fetch visitor profile data at this time';
}
// ... you can now substitute the contents of the $autochartVisitorSummary string into your email template

?>

<html>
 <head>
  <title>My PHP Test</title>
  <style>
      html { font-family: 'Helvetica Neue', Helvetica, Arial }
      </style>
 </head>
 <body>
     <h3>Autochart Visitor Profile</h3>
    <?php echo $autochartVisitorSummary; ?>
 <hr>
 </body>
</html>