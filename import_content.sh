#!/bin/bash
# echoes import statements, copies tables' uploads folders
# 1 path to zipped import file's derectory
# 2 zip file name
# 3 tables
# 4 ext?

cd $1
unzipped=`unzip -qql $2 | head -n1 | awk '{ print $4}'` # ToDo does this work with spaces in the zip file name?
unzip -qq $2 && cd $unzipped

current_table=_
for file in `find $3 -type f -name \*.xml | grep -v ^$1/_admin*  | sort`; do
	[[ $file =~ (.*)/(.*).xml ]]

	if [ $current_table != ${BASH_REMATCH[1]} ]; then # we are on the first row of a table
		current_table=${BASH_REMATCH[1]}
		echo 'TRUNCATE TABLE '$current_table\;	

		# uploads folder
		rm -rf ../../$current_table
		[ -d _admin/uploads/$current_table ] && cp -r _admin/uploads/$current_table ../../

		# if [ $current_table = 'page' ]; then should remove all existing page symlinks 
	fi;
	ORIGINAL_IFS=${IFS}
	IFS=\>

	fields=()
	values=()

	i=0;
	for word in $(<$file); do
		i=$((i+1));
		if (( $i % 2 )); then
			if [ $i -gt 1 ]; then
				word=${word:1}
			fi;
			word=${word::-1}
			fields+=( ${word//<field name=\"/}, )
		else
			word=${word//<\/field/}
			if [ $current_table = "page" ] && [ ${fields[-1]} = "url," ]; then
				ln -s ../../src/page.php ../../../../${word//<\/field/}
			fi;
			word=\"${word}\",
			word=${word//\&quot;/\\\"}
			word=${word//\&amp;/&}
			word=${word//\&lt;/<}
			values+=( $word )
		fi;
	done

	fields=${fields[@]}
	values=${values[@]}

	echo INSERT INTO ${BASH_REMATCH[1]}\(id, ${fields::-1}\) VALUES\(${BASH_REMATCH[2]}, ${values::-1}\)\; | sed 's/&gt;/>/g'
	IFS=${ORIGINAL_IFS}
done


# Extentions
if [ -z '$4' ] && [ $4 = 'ext' ]; then
	rm -rf ../../ext
	cp -r _admin/ext ../../../
fi;

cd ..
rm -rf $unzipped