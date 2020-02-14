#!/bin/bash
# echoes import statements, copies tables' uploads folders
# 1 path to zipped import file's derectory
# 2 zip file name
# 3 tables

cd $1
unzipped=`unzip -qql $2 | head -n1 | awk '{ print $4}'`
unzip -qq $2 && cd $unzipped

current_table=_
for file in `find $3 -type f -name \*.xml | grep -v ^$1/_admin*  | sort`; do
	[[ $file =~ (.*)/(.*).xml ]]

	if [ $current_table != ${BASH_REMATCH[1]} ]; then
		current_table=${BASH_REMATCH[1]}
		rm -rf ../../$current_table # delete uploads folder on target project
		cp -r _admin/uploads/$current_table ../../ # copy uploads folder to target project
		echo 'TRUNCATE TABLE '$current_table\;	
	fi
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
			word=\"${word//<\/field/}\",
			word=${word//\&quot;/\"}
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

cd ..
rm -rf $unzipped