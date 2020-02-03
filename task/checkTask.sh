#!/bin/bash
source /etc/profile
for v in dealSource.php dealVisiterQueue.php checkTables.php addSourceCache.php dealWailian.php
do
PythonPid=`ps -ef | grep $v | grep -v grep | wc -l `

if [ $PythonPid -eq 0 ];
        then
        #echo $v
        cd $1
        #echo $1
        #a= php $v
        #echo $a
        #nohup php $v &
        if [ "$v" == "dealVisiterQueue.php" ];then
                i=1
                while [ $i -le 3 ]
                do
                         nohup php $v $i &
                         let i++
                done
        else
                 nohup php $v &
        fi
fi
done

