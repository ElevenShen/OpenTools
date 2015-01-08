#!/usr/bin/php
<?php
### require local whois or jwhois command ###

date_default_timezone_set("Asia/Shanghai");

if (count($argv) != 2) {
        echo "Usage: " . $argv[0] . " <Domain Or DomainList[domain1,domain2] Or ListFile]>\n";
        exit;
}


$domain_input = $argv[1];
$check_output = array();
$array_remaining_date = array();
$i = 0;
$id = 1;
$config_filter_format = array("Updated Date", "Creation Date", "Creation date", "Expiration Date", "Registrant Organization", 
                        "Registration Date", "Registrant:", "Name Server", "Registry Expiry Date", "Domain Name Commencement Date",
                        "Expiry", "Owner", "NS 1", "Expiry Date");
$config_convert_format = array("Creation Date" => "Registration Date", "Creation date" => "Registration Date",
                        "Domain Name Commencement Date" => "Registration Date",
                        "Registrant" => "Registrant Organization", "Expiry Date" => "Expiration Date",
                        "Expiry" => "Expiration Date", "Owner" => "Registrant Organization", "NS 1" => "Name Server",
                        "Registry Expiry Date" => "Expiration Date", "Registrar Registration Expiration Date" => "Expiration Date");
$config_expiration_format = array("Expiration Date", "Registry Expiry Date", "Registrar Registration Expiration Date", "Expiry");
$config_output_format = array("Expiration Date", "Registration Date", "Name Server", "Registrant Organization");

$is_echo_msg = "yes"; // yes or no
$sleep_time = 1; // sleep time, Default: 1 second

### require local MTA ###
$is_send_mail = "no"; // yes or no
$mail_adminname = "DomainCheck"; 
$mail_adminmail = ""; // email: name@domain
$mail_noreply = ""; // email
$mail_to = ""; // email
$mail_subject = "Domain CheckList";


if (is_file($domain_input)) {
    $domains = file($domain_input);
} else {
    $domains = explode(",", $domain_input);
}

foreach ($domains AS $domain) {
    $domain = trim($domain);
    check_whois($domain);
    sleep($sleep_time);
}

$message = "\n";
$message_mail = "<br>";
ksort($array_remaining_date, SORT_NUMERIC);
foreach ($array_remaining_date AS $k => $v) {
    foreach ($v AS $kk => $vv) {
        if ($i++ >= 5) {
            break;
        }
        if ($i == 1) {
            $mail_subject = $mail_subject . " [" . $check_output[$vv]["remaining_date"] . "]";
        }
        $message .= $vv . " 域名注册将在 " . $check_output[$vv]["remaining_date"] . " 后过期\n";
        $message_mail .= $vv . " 域名注册将在 " . $check_output[$vv]["remaining_date"] . " 后过期<br>";
    }
}
$message .= "\n\n";
$message_mail .= "<br><br>\n";

$message .= " | 编号 | 域名 | 剩余时间 | 过期时间 | 注册时间 | 域名服务商 | 注册主体 |\n";
$message_mail .= ' <table width="1100" border="1" cellpadding="2" cellspacing="0" bordercolorlight="#000000" bordercolordark="#FFFFFF" bgcolor="#FFFFEE">
        <tr><th>编号</th><th>域名</th><th>剩余时间</th><th>过期时间</th><th>注册时间</th><th>域名服务商</th><th>注册主体</th></tr>';
foreach ($domains AS $domain) {
    $domain = trim($domain);

    if (!isset($check_output[$domain]["remaining_date"])) {
        $check_output[$domain]["remaining_date"] = " NULL ";
    }

    if ($check_output[$domain]["remaining_date"] <= 90) {
        $bgcolor = "bgcolor='red'";
    } else {
        $bgcolor = "";
    }

    $message .= " | $id | " . $domain . " \t| " . $check_output[$domain]["remaining_date"] . " | ";
    $message_mail .= "<tr $bgcolor><td>" . $id++ . "</td><td>" . $domain . "</td><td>" . $check_output[$domain]["remaining_date"] . "</td>";
    foreach ($config_output_format AS $key) {
        if (!isset($check_output[$domain][$key])) {
            $message .= " NULL \t| ";
            $message_mail .= " <td> NULL </td> ";
        } else {
            $message .= $check_output[$domain][$key] . " \t| ";
            $message_mail .= "<td>" . $check_output[$domain][$key] . " </td>";
        }
    }
    $message .= "\n";
    $message_mail .= "</tr>\n";
}
$message .= "\n";
$message_mail .= "</table><br><br>\n";

if ($is_echo_msg == "yes") {
    echo $message;
}
if ($is_send_mail == "yes") {
    send_mail($mail_to, $mail_subject, $message_mail);
}

function send_mail($mail_to, $mail_subject, $message) {
    global $mail_adminname, $mail_noreply, $mail_adminmail;

    $subject = "=?utf-8?B?".base64_encode($mail_subject)."?=";

    $headers  = 'MIME-Version: 1.0' . "\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\n";
    $headers .= "From: =?utf-8?B?".base64_encode($mail_adminname)."?= <" . $mail_noreply . ">" . "\n";
    $headers .= 'Cc : '. $mail_adminmail . "\n";
    $headers .= 'References: <'.date("Ym").'@domaincheck>' . "\n";
    $headers .= "\n\r";

    if ($mail_to != "" and $message != "") {
            mail($mail_to, $subject, $message, $headers);
    }
}

function check_whois ($domain) {
    global $config_filter_format, $check_output, $config_expiration_format, $config_convert_format, $array_remaining_date, $sleep_time;

    $config_match = implode('|', $config_filter_format);

    if (!@exec("whois $domain", $result)) {
        $result = array();
    	@exec("whois $domain", $result);
        sleep($sleep_time);
        @exec("whois $domain", $result);
    }

    foreach ($result AS $value) {
        if (preg_match("/:/", $value) and preg_match("/^\s+$config_match/", $value)) {
            $split_value = preg_split("/:/", $value);
            $k = trim($split_value[0]);
            $v = trim($split_value[1]);
            if (isset($config_convert_format[$k])) {
                $k = $config_convert_format[$k];
            }
            if (isset($check_output[$domain][$k])) {
                continue;
            }
            if (preg_match("/Date/i", $k)) {
                $v = preg_replace("/\s+\w+\s*$/", "", $v);
                $v = preg_replace("/T\d+$/", "", $v);
                $v = preg_replace("/\//", "-", $v);
                $v = date("Y-m-d", strtotime($v));
            }
            if (in_array($k, $config_expiration_format)) {
                $remaining_date = intval((strtotime($v) - time()) / 86400) . "天";
                if ($remaining_date < -100) {
                    continue;
                }
                $check_output[$domain]["remaining_date"] = $remaining_date;
                $array_remaining_date[$remaining_date][$domain] = $domain;
            }
            $check_output[$domain][$k] = $v; 
        }
    }
}

?>
