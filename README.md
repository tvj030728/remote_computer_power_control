**1\. HomeBridge 가 무엇인가? & 설치하기**

홈 브릿지는, 여러 플랫폼의 IOT 기기들을 하나로 묶어 관리할 수 있는 오픈소스 프로그램입니다.  
홈 브릿지를 통해 애플 홈에 기기를 등록하고 사용할 수 있습니다.

[https://homebridge.io/](https://homebridge.io/)

우선, 공식 홈페이지를 참고하여 HomeBridge 를 설치해줍니다. (커맨드 몇줄이면 설치가 가능해서 따로 적지는 않을게요.)

(\*Apple TV 또는 Apple Homepod과 같은 네트워크에 연결된 기기에 설치하면 외부에서도 접근할 수 있습니다.)

(해당 기기가 없다면, 해당 장비와 같은 네트워크에 연결되었을 때만 접근 가능합니다. / 오라클 같은 클라우드 서비스에 설치하면 안되고, 집 네트워크에 연결되어있어야합니다.)

설치 후, [http://ip.address:8581/](http://ip.address:8581/) 로 접속하면 아래와 같은 화면이 뜹니다.

![](https://blog.kakaocdn.net/dn/b99qwh/btr5sgLNGeQ/6dqfkEmA2iCBzuRjYIaYsk/img.png)

화면에 보이는 QR코드를 스캔하면 애플 홈에 Home Bridge 를 등록할 수 있습니다.

**2\. 컴퓨터 제어를 위한 프로그램 설정하기**

컴퓨터의 상태를 확인하고, 원격으로 종료하기 위해서 프로그램을 하나 받아줍니다.

[https://github.com/karpach/remote-shutdown-pc/releases](https://github.com/karpach/remote-shutdown-pc/releases)

프로그램을 받고 실행하면, (안될경우 우측 하단 시스템 트레이 아이콘을 눌러 실행해주세요)

![](https://blog.kakaocdn.net/dn/QCGnk/btr5D162Stg/uV2n9Pggaa5SFwBGjwuag1/img.png)

이와 같이 설정창이 뜹니다.

Auto load at Windows startup 을 체크해주세요.

Secret Code 는 비밀번호 / Port 는 접근을 위한 포트입니다. 입맛에 맞게 설정해주세요. (외부에서 접근이 가능하도록 하고 싶으시다면, 포트포워딩 잊지마세요)

설정 후, [http://아이피:포트/](/) 에 접속했을 때, Ok 가 표시됩니다.

**3\. 컴퓨터 전원 제어 API 주소 얻기**

컴퓨터가 외부에서 접속할 수 있다면, 이용할 수 있도록 제 서버에 API를 올려 공개해둘 예정입니다만,

내부망에서만 접근할 수 있도록 설정하실 예정이시라면, [\[GITHUB\]](https://github.com/tvj030728/remote_computer_power_control/blob/main/api.php) 여기서 php 파일을 내려받아 웹서버에 올려주시면 될 것 같습니다.

가이드에서는 제 서버에서 요청하는걸 기준으로 설명합니다.

**3-1. SmartThings 스마트플러그 이용하기**

Tuya, Tapo, Mi 등등 서드파티 플러그라도, 스마트싱스에 등록할 수 있다면 모두 이용 가능합니다.

우선, 컴퓨터 바이오스에서 전원 -> 전원 복구시 컴퓨터를 켜는 옵션을 변경해주십시오. \[Power -> AC power\] 바이오스마다 설정법이 조금 씩 다를겁니다.

대충 전원 복구되면 전원 켜지는걸 설정해주시면 됩니다.

[https://account.smartthings.com/tokens](https://account.smartthings.com/tokens) 에 접속합니다.

![](https://blog.kakaocdn.net/dn/dhz5TA/btr5vt5dcjS/HJRAphdAd012fZHsdwtTC1/img.png)

새 토큰 만들기를 클릭합니다.

![](https://blog.kakaocdn.net/dn/cwiJEz/btr5DSWxHm1/OFgkFCYHqhYYGmq0t8YH51/img.png)

위와 같이 권한을 설정합니다.

만들어진 토큰을 복사합니다.

[https://blackdeery.github.io/st_helper/](https://blackdeery.github.io/st_helper/) 에 접속하여 토큰을 넣고 장치를 조회합니다.

![](https://blog.kakaocdn.net/dn/beFUrH/btr5sgZjUr3/mEKnzDjssZHvzkKuTnnzr0/img.png)

컴퓨터가 연결된 플러그의 deviceid를 복사합니다.

// 상태 확인 URL (컴퓨터가 켜져있을 경우, 1 꺼져있을 경우 0 출력됨)

[https://qwer.pw/local_api/remote_pc_power_control.php?type=status&remote_ip=컴퓨터IP주소&remote_port=포트](https://qwer.pw/local_api/remote_pc_power_control.php?type=status&remote_ip=%EC%BB%B4%ED%93%A8%ED%84%B0IP%EC%A3%BC%EC%86%8C&remote_port=%ED%8F%AC%ED%8A%B8)

// 컴퓨터 켜기

[https://qwer.pw/local_api/remote_pc_power_control.php?type=st&action=on&token=토큰&device_id=디바이스ID](https://qwer.pw/local_api/remote_pc_power_control.php?type=st&action=on&token=%ED%86%A0%ED%81%B0&device_id=%EB%94%94%EB%B0%94%EC%9D%B4%EC%8A%A4ID)

// 컴퓨터 끄기

[https://qwer.pw/local_api/remote_pc_power_control.php?type=st&action=off&token=토큰&device_id=디바이스ID&remote_ip=컴퓨터IP주소&remote_pw=비밀번호&remote_port=포트&waiting_time=20](https://qwer.pw/local_api/remote_pc_power_control.php?type=st&action=off&token=%ED%86%A0%ED%81%B0&device_id=%EB%94%94%EB%B0%94%EC%9D%B4%EC%8A%A4ID&remote_ip=%EC%BB%B4%ED%93%A8%ED%84%B0IP%EC%A3%BC%EC%86%8C&remote_pw=%EB%B9%84%EB%B0%80%EB%B2%88%ED%98%B8&remote_port=%ED%8F%AC%ED%8A%B8&waiting_time=20)

\* waiting_time 은 컴퓨터를 종료하는 명령이 전달된 후 플러그를 끄기 까지의 시간입니다.

**3-2. WOL 이용하기**

WOL은... 알아서 설정하시면 될 것 같습니다..

// 상태 확인 URL (컴퓨터가 켜져있을 경우, 1 꺼져있을 경우 0 출력됨)

[https://qwer.pw/local_api/remote_pc_power_control.php?type=status&remote_ip=컴퓨터IP주소&remote_port=포트](https://qwer.pw/local_api/remote_pc_power_control.php?type=status&remote_ip=%EC%BB%B4%ED%93%A8%ED%84%B0IP%EC%A3%BC%EC%86%8C&remote_port=%ED%8F%AC%ED%8A%B8)

// 컴퓨터 켜기

[https://qwer.pw/local_api/remote_pc_power_control.php?type=wol&action=on&remote_ip=컴퓨터IP주소&remote_mac=컴퓨터MAC주소](https://qwer.pw/local_api/remote_pc_power_control.php?type=wol&action=on&remote_ip=%EC%BB%B4%ED%93%A8%ED%84%B0IP%EC%A3%BC%EC%86%8C&remote_mac=%EC%BB%B4%ED%93%A8%ED%84%B0MAC%EC%A3%BC%EC%86%8C)

// 컴퓨터 끄기

[https://qwer.pw/local_api/remote_pc_power_control.php?type=wol&action=off&remote_ip=컴퓨터IP주소&remote_pw=비밀번호&remote_port=포트](https://qwer.pw/local_api/remote_pc_power_control.php?type=wol&action=off&remote_ip=%EC%BB%B4%ED%93%A8%ED%84%B0IP%EC%A3%BC%EC%86%8C&remote_pw=%EB%B9%84%EB%B0%80%EB%B2%88%ED%98%B8&remote_port=%ED%8F%AC%ED%8A%B8)

**4\. HomeBridge 설정하기**

홈브릿지 -> 플러그인에서 "homebridge http switch" 를 검색해서 설치해주세요.

![](https://blog.kakaocdn.net/dn/bAIwuw/btr5zP08j8g/azvrGp4bDCZUrGXVJaceUK/img.png)

설치 후, 설정 버튼을 눌러 아래와 같이 설정합니다.

![](https://blog.kakaocdn.net/dn/k0cv9/btr5D2ETzyB/EUwYwoNg4pUtDH3dT3ynk0/img.png)

```
{
    "accessory": "HTTP-SWITCH",
    "name": "컴퓨터",
    "switchType": "stateful",
    "onUrl": "컴퓨터 켜기 URL",
    "offUrl": "컴퓨터 끄기 URL",
    "statusUrl": {
        "url": "컴퓨터 상태 확인",
        "method": "GET"
    }
}
```

끝!
