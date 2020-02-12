#!/bin/bash
# echoes import statements
# 1 path to unzipped import file

current_table=_
for file in `find $1 -type f -name \*.xml | grep -v ^$1/_admin*  | sort`; do
	[[ $file =~ (.*)/(.*)/(.*).xml ]]

	if [ $current_table != ${BASH_REMATCH[2]} ]; then
		current_table=${BASH_REMATCH[2]}
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

	echo INSERT INTO ${BASH_REMATCH[2]}\(id, ${fields::-1}\) VALUES\(${BASH_REMATCH[3]}, ${values::-1}\)\; | sed 's/&gt;/>/g'
	IFS=${ORIGINAL_IFS}
done
