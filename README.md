#opstools 运维工具库
========

##checkdomain.php
####checkdomain 域名注册信息检查监控工具：
````
通过whois或jwhois命令检查域名注册时间、过期时间等值；
支持单域名、多域名(,号分割)、域名文件列表(每行一个域名)输入；
````

##pqlist.php
####pqlist 运维排期表工具：
````
支持按照指定周期进行排班；
支持按照指定时间提前排班；
支持顺序轮转或随机排班；
支持使用本地MTA发送排班邮件；

20141212新增功能:
支持配置同时值班人数；
支持配置每次值班持续天数；

````

###Usage
```
$ php pqlist.php 
2014-12-15 Monday -- 2014-12-21 Sunday user2[email2] user4[email4]  <br>
2014-12-22 Monday -- 2014-12-28 Sunday user3[email3] user5[email5]  <br>
2014-12-29 Monday -- 2015-01-04 Sunday user4[email4] user6[email6]  <br>
2015-01-05 Monday -- 2015-01-11 Sunday user5[email5] user1[email1]  <br>
2015-01-12 Monday -- 2015-01-18 Sunday user6[email6] user2[email2]  <br>
2015-01-19 Monday -- 2015-01-25 Sunday user1[email1] user3[email3]  <br>
2015-01-26 Monday -- 2015-02-01 Sunday user2[email2] user4[email4]  <br>
2015-02-02 Monday -- 2015-02-08 Sunday user3[email3] user5[email5]  <br>
2015-02-09 Monday -- 2015-02-15 Sunday user4[email4] user6[email6]  <br>
2015-02-16 Monday -- 2015-02-22 Sunday user5[email5] user1[email1]  <br>
2015-02-23 Monday -- 2015-03-01 Sunday user6[email6] user2[email2]  <br>
2015-03-02 Monday -- 2015-03-08 Sunday user1[email1] user3[email3]  <br>
2015-03-09 Monday -- 2015-03-15 Sunday user2[email2] user4[email4]  <br>

```



##checkurl.pl
一个URL下载状态及时间检测脚本：
能检测不同Dns Server的解析地址，能显示IP的地域信息；
能在DNS解析配置之前，测试CDN提供的CNAME地址的可用性；

###Usage

```
$ perl checkurl.pl 'http://fmn.xnimg.cn/robots.txt'
$ perl checkurl.pl 'http://fmn.xnpic.com.cdn20.com/robots.txt' fmn.xnimg.cn  ### Test CDN CNAME

http://fmn.xnimg.cn/robots.txt

####################
Dns Server IP：202.106.46.151 来自：北京市 联通
Web Server IP：111.206.169.4 来自：北京市 联通
111.206.169.4	http_code:200	 size_header:408	 size_download:26	 time_connect:0.004	 time_starttransfer:0.014	 time_total:0.014


####################
Dns Server IP：211.98.2.4 来自：北京市 铁通
Web Server IP：111.13.4.54 来自：北京市 移动
111.13.4.54	http_code:200	 size_header:348	 size_download:26	 time_connect:0.043	 time_starttransfer:0.109	 time_total:0.109


####################
Dns Server IP：202.96.128.143 来自：广东省广州市 电信
Web Server IP：113.142.3.221 来自：陕西省咸阳市 电信
113.142.3.221	http_code:200	 size_header:347	 size_download:26	 time_connect:0.022	 time_starttransfer:0.060	 time_total:0.060


####################
Dns Server IP：61.139.2.69 来自：四川省成都市 电信
Web Server IP：113.142.3.221 来自：陕西省咸阳市 电信
113.142.3.221	http_code:200	 size_header:347	 size_download:26	 time_connect:0.022	 time_starttransfer:0.045	 time_total:0.045


####################
Dns Server IP：202.38.64.1 来自：安徽省合肥市 中国科技大学
Web Server IP：58.205.210.40 来自：中国 教育网
58.205.210.40	http_code:200	 size_header:348	 size_download:26	 time_connect:0.015	 time_starttransfer:0.022	 time_total:0.022
```
