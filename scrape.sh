# echoes each value for that table and column 
# $1 path to db dump
# $2 table name
# $3 column name
# $4 prefix

INSERT=`cat $1 | grep 'INSERT INTO \`'$2'\`'`
FIND='INSERT INTO `'$2'` VALUES ('
INSERT=${INSERT//$FIND/,}
INSERT=${INSERT::-1}

for TUPLE in ${INSERT//)/ } ; do
	for i in $(seq 0 $(($3 + 2))) ; do
		url="${TUPLE%%,*}"; TUPLE="${TUPLE#*,}" # https://pubs.opengroup.org/onlinepubs/009695399/utilities/xcu_chap02.html
	done
	url=${url:1:-1}
	echo $4''$url
done