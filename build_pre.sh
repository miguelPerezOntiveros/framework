#!/bin/sh

rm -r projects/$1
mkdir -p projects/$1/admin/uploads

cd projects/$1/admin
ln -s ../../../src/index.php .
ln -s ../../../src/login.php .
cp -r ../../../src/viewer.php .

cd uploads
mkdir `echo $5 | sed 's/,/ /g'`
