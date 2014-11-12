#!/usr/bin/perl

use Data::Dumper;

$url = $ARGV[0];
$real_domain = $ARGV[1];
$url_tmpl = $url;

if ($url eq "") {
	printf "Usage: $0 <TestURL> <RealDomain Or Empty>\n";
	exit;
}

if ($url =~ /(.*?):\/\/(.*?)\//) {
	$hostname = $2;
} else {
	printf("fail to match domain name\n");
	exit;
}

if ($real_domain eq "") {
	$real_hostname = $hostname;
} else {
	$real_hostname = $real_domain; #Change CDN CNAME Domain
	$url =~ s/$hostname/$real_hostname/;
}
printf "\n$url\n\n";

# url template for later use
$url_tmpl =~ s@(.*?)://(.*?)/@$1://\%s/@;

@ns_list = (
	'114.114.114.114', #OpenDNS
	'223.5.5.5',       #AliDNS
	'219.141.140.10',  #北京电信
	'61.151.246.246',  #上海电信
	'202.96.128.143',  #广东电信
        '61.139.2.69' ,    #四川电信
	'202.106.46.151',  #北京联通
	'202.106.127.1',   #北京联通
        '202.102.152.3',   #山东联通
        '221.6.246.1',     #江苏联通
	'58.20.221.214',   #湖南联通
	'211.136.192.6',   #广东移动
	'211.137.96.205',  #四川移动
        '211.153.19.1',    #教育网北京
        '202.38.64.1',     #教育网中科大
        '202.120.223.6',   #上海理工大学
        '211.98.2.4',      #北京铁通
        '211.98.4.1',      #广州铁通
        '168.95.1.1',      #台湾
        '208.67.220.220' , #美国
        '8.8.8.8' ,        #GoogleDns
);

foreach $ns (@ns_list) {
	printf "####################\nDns Server ";
	printf `curl 'http://ip.cn/?ip=$ns' 2>/dev/null`;

	if ($ns !~ /^(\d{1,3}\.){3}\d{1,3}$/) {
		next;
	}
	$ret = qx/host $hostname $ns/;
	$ret =~ s/^.*?has address //gms;

	if ($ret =~ "not found") {
		printf "$hostname\n";
		$ret = qx/host $hostname $ns/;
		$ret =~ s/^.*?has address //gms;
	}

	@addr = sort(split(/\n/, $ret));

	foreach $addr (@addr) {
		if ($addr !~ /^(\d{1,3}\.){3}\d{1,3}$/) {
			next;
		}
		printf `curl 'http://ip.cn/?ip=$addr' 2>/dev/null`;

		$test_url = sprintf($url_tmpl, $addr);
		for ($i = 0; $i < 1; $i++) {
			print "$addr\t";
			print qx/curl -o \/dev\/null -s -w"http_code:\%{http_code}\\t size_header:\%{size_header}\\t size_download:\%{size_download}\\t time_connect:\%{time_connect}\\t time_starttransfer:\%{time_starttransfer}\\t time_total:\%{time_total}\\n" -H "Host: $real_hostname" $test_url/;
		}
		printf("\n");
	}
	printf("\n\n");
}
