<?php
$smartthings_token = $_GET['token'];
$smartthings_device_id = $_GET['device_id'];
$remote_ip = $_GET['remote_ip'];
$remote_pw = $_GET['pw'];
$remote_port = $_GET['port'];

if($_GET['type'] == "st") { // SMARTTHINGS SWITCH
    if ($_GET['action'] == "on"){
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, 'https://api.smartthings.com/v1/devices/'.$smartthings_device_id.'/commands');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"commands\":[{\"component\":\"main\",\"capability\":\"switch\",\"command\":\"on\"}]}");
        
        $headers = array();
        $headers[] = 'Authorization: Bearer '.$smartthings_token;
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
    } else if ($_GET['action'] == "off"){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://".$remote_ip.":".$remote_port."/".$remote_pw."/shutdown");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if($response === false) {
            echo 'Curl error: ' . curl_error($ch);
        } else {
            echo 'Response: ' . $response;
        }
        curl_close($ch);
    
        sleep(20);
    
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, 'https://api.smartthings.com/v1/devices/'.$smartthings_device_id.'/commands');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"commands\":[{\"component\":\"main\",\"capability\":\"switch\",\"command\":\"off\"}]}");
        
        $headers = array();
        $headers[] = 'Authorization: Bearer '.$smartthings_token;
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
    } else if ($_GET['action'] == "status"){
        $url = "http://".$remote_ip.":".$remote_port."/";
        $timeout = 1;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $timeout * 1000);
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpcode == 200) {
            echo "1";
        } else {
            echo "0";
        }
    } else {
        die("somthing wrong");
    }
}
?>