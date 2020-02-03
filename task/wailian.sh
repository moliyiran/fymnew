#!/bin/bash
source /etc/profile

cd $1
nohup php dealSourceTest.php $2 &
