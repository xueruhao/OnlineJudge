#!/bin/bash

###############################################################
# 判题端配置文件
# 默认加载项目根目录下.env配置
###############################################################
source "$(dirname $(dirname $(readlink -f "$0")))"/.env

###############################################################
# 如需自行配置，请将上面的source命令注释，然后取消下面的注释，并填写相关信息
# 以DB开头的是数据库连接信息
# JG_DATA_DIR      测试数据路径（可填绝对路径）
# JG_NAME          评测机名称
# JG_MAX_RUNNING=1 最大并行判题数，建议值为服务器内存(GB)初以2
###############################################################
#DB_HOST=127.0.0.1
#DB_PORT=3306
#DB_DATABASE=lduoj
#DB_USERNAME=lduoj
#DB_PASSWORD=123456789
#JG_DATA_DIR=storage/app/data
#JG_NAME="Master"
#JG_MAX_RUNNING=1