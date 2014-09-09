<?php
### format: username,email,mobile
$pq_userlist = array(
"user3,email3,135xxxxxxx3", 
"user2,email2,135xxxxxxx2",
"user4,email4,135xxxxxxx4",
"user1,email1,135xxxxxxx1",
"user5,email5,135xxxxxxx5",
);

$pq_week_cn = 0; // Chinese Weekname:  0 disable or 1 enable 
$pq_weekarray = array("周日","周一","周二","周三","周四","周五","周六");
$pq_period = "7";  // 7:week or 30:month
$pq_mode = "rotate";  // rotate or random
$pq_aheadtime = "3"; // days: ahead of schedule

### require local MTA ###
$mail_adminname = "pqlist"; 
$mail_adminmail = ""; // email: name@domain
$mail_noreply = ""; // email
$mail_to = ""; // email
$mail_subject = "Duty Schedule"; // 运维排班表

$array_userlist = get_userlist($pq_userlist);

$period_list = get_rotate_period();
send_mail($mail_to, $mail_subject, $period_list);

function send_mail($mail_to, $mail_subject, $period_list) {
	global $mail_adminname, $mail_noreply, $mail_adminmail;

        $subject = "=?utf-8?B?".base64_encode($mail_subject)."?=";

	$headers  = 'MIME-Version: 1.0' . "\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\n";
        $headers .= "From: =?utf-8?B?".base64_encode($mail_adminname)."?= <" . $mail_noreply . ">" . "\n";
        $headers .= 'Cc : '. $mail_adminmail . "\n";
        $headers .= 'References: <PQLIST.'.date("Ym").'@pqlist>' . "\n";
        $headers .= "\n\r";

	$message = "<h2>$period_list</h2>";

	if ($mail_to != "" and $mesage != "") {
	        mail($mail_to, $subject, $message, $headers);
	}
	echo $period_list;
}
function get_userlist($pq_userlist) {
	global $pq_period, $pq_mode;
	if ($pq_period < 30) {
		$p_number = date("W");
	} else {
		$p_number = date("m");
	}
	$i = 0;
	$u_count = count($pq_userlist);
	foreach ($pq_userlist AS $v) {
		$i++;
		$n = ($i + $p_number) % $u_count;

		//$a = preg_split("/,/", $v);
		$a = explode(',', $v); //这里不需要用正则函数  @李坚
		$r[$n] = $a; 
	}
	if ($pq_mode == "random") shuffle($r);

	return $r;
}
function get_rotate_period() {
	global $pq_period, $pq_mode, $array_userlist, $array_lately, $pq_lastuser, $pq_weekarray, $pq_week_cn, $pq_aheadtime, $mail_subject;

	$i = $pq_aheadtime;
	$output = "";
	$c = count($array_userlist);
	while ($i <= $pq_period+$pq_aheadtime-1) {
		$n = $i % $c;
		if ($pq_week_cn == 1 and $pq_weekarray != "") {
			$week = date("w", strtotime("+$i day"));
			$week_name = $pq_weekarray[$week];
		} else {
			$week_name = date("l", strtotime("+$i day"));
		}
		$output .= date("Y-m-d", strtotime("+$i day")) . " " 
			. $week_name . " "
			. $array_userlist[$n][0] . "[" . $array_userlist[$n][1] . "]" 
			. " <br>\n";
		$i++;
	}
	$i_s = $i - 1;
	$mail_subject = $mail_subject . " " . date("Y-m-d", strtotime("+$pq_aheadtime day")) . ' -- ' . date("Y-m-d", strtotime("+$i_s day"));
	return $output;
}
