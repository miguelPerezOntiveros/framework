#!/bin/sh

mysql -h $2 -P $5 -u $3 --password=$4 < ../projects/$1/$1.sql
