<?php

function posts($url, $post_data, $port = 8000)
{
    $s = json_decode('Not Found');
    //初始化
    $curl    = curl_init();
    $reqdata = http_build_query($post_data);
    $length  = strlen($reqdata);
    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
//    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
//    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
        [
            'Content-Type: application/x-www-form-urlencoded;charset=utf-8',
            'Content-Type: application/json; charset=utf-8',
            //"Content-Length: {$length}",
            //'X-Requested-With: XMLHttpRequest',
            //'Accept: application/json, text/javascript, */*; q=0.01',
            //'Accept-Encoding: gzip, deflate',
            //'Accept-Language: en-US,en;q=0.9',
            //'Pragma: no-cache',
            'Cache-Control: no-cache',
            //                    'User-Agent: PostmanRuntime/7.15.0',
            'Accept: */*',
            //                    'accept-encoding: gzip,deflate',
        ]);
    //异步
    curl_setopt($curl, CURLOPT_TIMEOUT, 0);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_PORT, $port);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $reqdata);
    $data = curl_exec($curl);
    if (false === $data) {
        $err = curl_error($curl);
    }
    curl_close($curl);

    return $data;
}

function send(string $api, array $data)
{
    $url =   '75.28.19.185'.'/api/v1'   . $api;
    return posts($url, $data);
}

function addcards($data)
{
    $dataExpample = [
        // Ip: $('#Ip').val(),
        // Port: $('#Port').val(),
        // User: $('#User').val(),
        // Pwd: $('#Pwd').val(),
        // CardNo: $('#CardNo').val(),
        // byName: $('#byName').val(),
        // byCardPassword: $('#byCardPassword').val(),
        // EmployeeNo: $('#EmployeeNo').val(),
        // byCardValid: $('#byCardValid').val(),
        // EndTime: $('#EndTime').val(),
    ];
    $s            = send('/addcards', $data);
    return issucc($s);
}

function delcards($data)
{
    $dataExpample = [
        // Ip: $('#Ip').val(), Port: $('#Port').val(), User: $('#User').val(), Pwd: $('#Pwd').val(),
        // CardNo: i,
        // byName: i,
        // byCardPassword: $('#byCardPassword').val(),
        // EmployeeNo: $('#EmployeeNo').val(),
        // byCardValid: 0
    ];
    $s            = send('/addcards', $data);
    return issucc($s);

}

function addcars($data)
{
    $dataExpample = [
        // Ip: $('#Ip').val(), Port: $('#Port').val(), User: $('#User').val(),
        // Pwd: $('#Pwd').val(),
        // sLicense: $('#sLicense').val(),
        // sCardNo: $('#sCardNo').val(),
        // byPlateColor: $('#byPlateColor').val(),
        // byListType: $('#byListType').val(),
        // sOperateIndex: $('#sOperateIndex').val()
    ];
    $s            = send('/addcars', $data);
    return issucc($s);
}

function delcars($data)
{
    $dataExpample = [
        // Ip: $('#Ip').val(), Port: $('#Port').val(), User: $('#User').val(),
        // Pwd: $('#Pwd').val(),
        // sLicense: $('#sLicense').val(),
        // sCardNo: $('#sCardNo').val(),
        // byPlateColor: $('#byPlateColor').val(),
        // byListType: $('#byListType').val(),
        // sOperateIndex: $('#sOperateIndex').val()
    ];
    $s            = send('/delcars', $data);
    return issucc($s);
}

function addpic($data)
{
    $dataExpample = [
        // Ip: $('#Ip').val(), Port: $('#Port').val(), User: $('#User').val(), Pwd: $('#Pwd').val(),
        // m_csFacePath: $('#m_csFacePath').val(),
        // CardNo: $('#CardNo').val()
    ];
    $s            = send('/addcardpic', $data);
    return issucc($s);

}

function delpic($data)
{
    $dataExpample = [
        // Ip: $('#Ip').val(), Port: $('#Port').val(), User: $('#User').val(), Pwd: $('#Pwd').val(),
        // m_csFacePath: $('#m_csFacePath').val(),
        // CardNo: $('#CardNo').val()
    ];
    $s            = send('delcardpic', $data);
    return issucc($s);
}

function getcards($data)
{
    $dataExpample = [
        // Ip: $('#Ip').val(), Port: $('#Port').val(), User: $('#User').val(), Pwd: $('#Pwd').val(),
    ];
    $s = send('/getcards',$data);
    return $s;
}
function issucc($s)
{
    $json = json_decode($s, true);
    if (strpos($json['result'], 'SUCC') !== false)
        return 1;
    return 0;
}