#!/bin/bash

#crontab 设置:
#0 1 * * * sh /home/bin/mysql.sh
#/data/pwww/mysql_data_bak表示备份文件存放目录
mysqldump -uroot -p密码 数据库 >/data/pwww/mysql_data_bak/date_$(date +%Y%m%d_%H%M%S).sql
#下面这句是删除7天前的备份，可以注释掉。记住不要填错目录造成误删引起系统崩溃
find /data/pwww/mysql_data_bak/ -mtime +7 -name "*.sql" -exec rm -rf {} \;