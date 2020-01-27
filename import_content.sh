#!/bin/bash
# 1 path to unzipped import file

for file in `find $1 -type f -name \*.xml | sort`; do
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
			fields+=( ${word//<field name=/}, )
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

	[[ $file =~ (.*)/(.*)/(.*).xml ]]
	echo insert into ${BASH_REMATCH[2]}\(\"id\", ${fields::-1}\) values\(${BASH_REMATCH[3]}, ${values::-1}\)\; | sed 's/&gt;/>/g'
	IFS=${ORIGINAL_IFS}
done
