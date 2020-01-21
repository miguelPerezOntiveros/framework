mysqldump -h 172.17.0.2 --xml --no-create-info -u root --password=admin miguelp > dump.sql
csplit dump.sql '/<table_data name=".*">/' {*}
rm dump.sql xx00 # header doesn´t have table data

for file in `ls x*`; do
	file_name=`sed -n '1p' $file | sed -r 's/\t<table_data name="(.*?)">/\1/'`;
	mkdir $file_name
	mv $file $file_name/rows.xml
	cd $file_name
		sed -i '1d;$d' rows.xml # first and last lines are not about rows
		csplit rows.xml '/<row>/' {*}
		rm rows.xml xx00 # first one will be empty
		# delete the first tab from each line?
		# rename row files by their id
		# what happens when there are more than 99 rows?
	cd ..
done