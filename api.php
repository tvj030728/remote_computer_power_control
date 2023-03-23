<?php

/*

사용 예시:

// 컴퓨터 ON OFF 체크. 1 켜짐 / 2 꺼짐 
api.php?type=status&remote_ip=pc.local.ivlis.kr&remote_port=5001


// 스마트싱스를 이용한 전원 켜기 [type, action, token, device_id 는 필수입니다.]
api.php?type=st&action=on&token=aaaa-aaaaaa-aaaaaaa-aaaaaa-aaaaa&device_id=bbbbb-bbbbb-bbbbb-bbbbb

// 스마트싱스 및 PC 제어를 이용한 전원 끄기 [type, action, token, device_id, remote_ip, remote_pw, remote_port 는 필수입니다. waiting_time 은 선택입니다.(기본값 20초)]
api.php?type=st&action=off&token=aaaa-aaaaaa-aaaaaaa-aaaaaa-aaaaa&device_id=bbbbb-bbbbb-bbbbb-bbbbb&remote_ip=123.234.123.234&remote_pw=1234&remote_port=8333&waiting_time=20


// wol을 이용한 전원 켜기 [type, action, remote_ip, remote_mac 는 필수입니다.]
api.php?type=wol&action=on&remote_ip=123.123.123.123&remote_mac=AA-AA-AA-AA-AA-AA

// wol을 이용한 전원 끄기 [type, action, remote_ip, remote_pw, remote_port 는 필수입니다.]
api.php?type=wol&action=off&remote_ip=123.123.123.123&remote_pw=1234&remote_port=8333
*/

$smartthings_token = $_GET['token'];         //smartthings only
$smartthings_device_id = $_GET['device_id']; //smartthings only
$waiting_time = $_GET['waiting_time'];       //smartthings only
$remote_ip = $_GET['remote_ip'];             //both
$remote_pw = $_GET['remote_pw'];             //both
$remote_port = $_GET['remote_port'];         //both
$remote_mac = $_GET['remote_mac'];           //wol only AA-AA-AA-AA-AA-AA FORMAT

if(!isset($_GET['waiting_time'])) $waiting_time = 20;
// waiting time 은 컴퓨터 종료 요청을 보낸 후, IOT 플러그를 끄기 까지의 시간입니다. 기본 값은 20초 입니다.

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
    
        sleep($waiting_time);
    
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
    } else {
        die("somthing wrong");
    }
} else if ($_GET['type'] == "wol"){
    if ($_GET['action'] == "on"){
        function wol($broadcast, $mac) {
            if (strstr($mac, "-")) {
                $mac_array = explode('-', $mac);
            } else {
                $mac_array = explode(':', $mac);
            }
            $hwaddr = '';
            foreach($mac_array AS $octet) {
                $hwaddr .= chr(hexdec($octet));
            }
            $packet = '';
            for ($i = 1; $i <= 6; $i++) {
                $packet .= chr(255);
            }
         
            for ($i = 1; $i <= 16; $i++) {
                $packet .= $hwaddr;
            }
         
            $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
            if ($sock) {
                $options = socket_set_option($sock, SOL_SOCKET, SO_BROADCAST, true);
                if ($options >=0) {    
                    $e = socket_sendto($sock, $packet, strlen($packet), 0, $broadcast, 7);
                    socket_close($sock);
                }    
            }
        }
         
        wol($remote_ip, $remote_mac);
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
    } else {
        die("somthing wrong");
    }
} else if ($_GET['type'] == "status"){
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
}
?>