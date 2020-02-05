# builds a content export folder
# $1 db_host
# $2 db_user
# $3 db_pass
# $4 db_port
# $5 db_name
# $6 tables
# $7 zip file path

cd $7
# TODO need to specify tables names
mysqldump -h $1 -P $4 --xml --no-create-info -u $2 --password=$3 $5 $6 > dump.sql
sed -i '$d' dump.sql # closing mysqldump tag
sed -i '$d' dump.sql # closing database tag
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
			sed -i '1d' $file # first line only has the id field
			sed -i -e 's/^\t\t//' $file
			mv $file $file_name.xml
			# TODO what happens when there are more than 99 rows?
		done
	cd ..
done