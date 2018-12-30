# framework 1.5
### TODO
- improve documentation
- I should look into extending the projects from within, issuing create table statements
- I should allow for back and forward browser button functionality at index.php (you can't currently reach the same tab you were on)
- I shouldnt be changing the actual file names on image uploads
- I should reverse engineer sql dumps to my yaml syntax
- change the user and user type table names to avoid collisions? 
- allow UI to ask for a DB port
- should access to reference imply access to referenced column?
- open project in new tab?
- encript passwords in db
- make sure no sql injection is possile
- make sure we get feedback if db couldn´t be created. PHP will know if the 3 inputs are not submited.
- make sure we get feedback on sql errors.
- add 'show' prop to individual columns
- stop usign the temp folder?
- address the todo's on index.php
- modifying a row with image is not autofilling all values

### Running php dev server 
	./start.sh - will start mysql and php dev server
	you should have mysql installed on your mac and '/usr/local/mysql/bin' in your PATH

### JS yaml library used
https://www.npmjs.com/package/yamljs

### When you click 'Submit'

#### index.php
- writes temp/projectName/config.inc.php
- writes temp/projectName/projectName.yml
- writes temp/projectName/projectName.sql
- runs ./build.sh projectName db_host db_user db_pass

#### build.sh
- creates projects/projectName/admin
- writes src/* files into it
- writes projects/projectName/admin/db_connection.inc.php
- writes projects/projectName/admin/session.inc.php
- moves temp/projectName/*.php into projects/projectName/admin
- moves temp/projectName/*.* into projects/projectName
- removes temp/projectName
- runs projects/projectName/projectName.sql against db
