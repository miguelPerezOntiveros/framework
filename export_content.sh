# builds a content export zip
# $1 db_host
# $2 db_user
# $3 db_pass
# $4 db_port
# $5 db_name
# $6 zip file path

cd $6
mysqldump -h $1 -P $4 --xml --no-create-info -u $2 --password=$3 $5 > dump.sql
csplit dump.sql '/<table_data name=".*">/' {*}
rm dump.sql xx00 # xml header doesn´t have table data

for file in `ls x*`; do # traverse tables
	file_name=`sed -n '1p' $file | sed -r 's/\t<table_data name="(.*?)">/\1/'`
	mkdir $file_name
	mv $file $file_name/rows.xml
	cd $file_name
		sed -i '1d;$d' rows.xml
		if [ ! -s rows.xml ]; then # no rows
			rm rows.xml
			cd ..
			continue
		fi
		csplit rows.xml '/<row>/' {*}
		rm rows.xml xx00 # first one will be empty
		for file in `ls x*`; do # traverse rows
			sed -i '1d;$d' $file
			file_name=`cat $file | grep -m 1 '<field name="id">' | sed -r 's/\t+<field name="id">(.*?)<\/field>/\1/'`
			sed -i -e 's/^\t\t//' $file
			mv $file $file_name.xml
			# what happens when there are more than 99 rows?
		done
	cd ..
done