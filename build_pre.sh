#!/bin/sh

rm -r ../projects/$1
mkdir -p ../projects/$1/admin/uploads ../projects/$1/admin/ext ../projects/$1/admin/exports

cd ../projects/$1/admin
ln -s ../../../src/index.php .
ln -s ../../../src/login.php .

cd uploads
mkdir `echo $5 | sed 's/,/ /g'`
