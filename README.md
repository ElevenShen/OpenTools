#opstools 运维工具库
========

##checkurl.pl
一个URL下载状态及时间检测脚本，能检测不同Dns Server的解析地址，能显示IP的地域信息；

###Usage

```
$ perl checkurl.pl 'http://fmn.xnimg.cn/robots.txt'

http://fmn.xnimg.cn/robots.txt

####################
Dns Server IP：202.106.46.151 来自：北京市 联通
IP：111.206.169.4 来自：北京市 联通
111.206.169.4	http_code:200	 size_header:408	 size_download:26	 time_connect:0.004	 time_starttransfer:0.014	 time_total:0.014


####################
Dns Server IP：211.98.2.4 来自：北京市 铁通
IP：111.13.4.54 来自：北京市 移动
111.13.4.54	http_code:200	 size_header:348	 size_download:26	 time_connect:0.043	 time_starttransfer:0.109	 time_total:0.109


####################
Dns Server IP：202.96.128.143 来自：广东省广州市 电信
IP：113.142.3.221 来自：陕西省咸阳市 电信
113.142.3.221	http_code:200	 size_header:347	 size_download:26	 time_connect:0.022	 time_starttransfer:0.060	 time_total:0.060


####################
Dns Server IP：61.139.2.69 来自：四川省成都市 电信
IP：113.142.3.221 来自：陕西省咸阳市 电信
113.142.3.221	http_code:200	 size_header:347	 size_download:26	 time_connect:0.022	 time_starttransfer:0.045	 time_total:0.045


####################
Dns Server IP：202.38.64.1 来自：安徽省合肥市 中国科技大学
IP：58.205.210.40 来自：中国 教育网
58.205.210.40	http_code:200	 size_header:348	 size_download:26	 time_connect:0.015	 time_starttransfer:0.022	 time_total:0.022
```
