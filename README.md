# framework 1.5
//TODO: make this pretty
uploads folder is not being created so no images can be uploaded.
I should just create a bin/start.sh script
I should make sure the yml and sql are being saved
I should look into extending the projects from within, issuing create table statements
I should allow for back and forward browser button functionality at index.php (you can't currently reach the same tab you were on)
I shouldnt be changin the actual files name on image uploads
The framework should store the yml file
I should reverse engineer sql dumps to my yaml syntax

### Running php dev server 
	./start.sh - will start mysql and php dev server

	you should have mysql installed on your mac and the '/usr/local/mysql/bin' in your PATH
### JS yaml library used
https://www.npmjs.com/package/yamljs

#### index.php
writes temp/projectName/config.inc.php
writes temp/projectName/projectName.sql
runs ./build.sh projectName db_host db_user db_pass

#### build.sh
creates projects/projectName/admin
writes src/* files into it
writes projects/projectName/admin/db_connection.inc.php
writes projects/projectName/admin/session.inc.php
writes temp/projectName/*.php into projects/projectName/admin
writes temp/projectName/*.sql into projects/projectName
removes temp/projectName
runs projects/projectName/projectName.sql against db
