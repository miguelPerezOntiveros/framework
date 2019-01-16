# framework 1.5
### TODO
- improve documentation
- I should look into extending the projects from within, issuing create table statements
- I should allow for back and forward browser button functionality at index.php (you can't currently reach the same tab you were on)
- I should reverse engineer sql dumps to my yaml syntax
- change the user and user type table names to avoid collisions? 
- should access to reference imply access to referenced column?
- open project in new tab?
- encript passwords in db
- make sure no sql injection is possile
- make sure we get feedback if db couldn´t be created. PHP will know if the 3 inputs are not submited.
- make sure we get feedback on sql errors.
- add 'show' prop to individual columns
- address the todo's on index.php
- Figure out a way to create content that is not published sucerely (possibly the where parameter on the read service)
- Upgrade to bootstrap 4.0
- Am I able to override what I show for a column?
- Change over to mysqli
- Revise logging, currently only using error_log
- Should the menu really be generated by the backend?
- You can notice the sidebar gets split while the animation is playing
- revise footer, add links/icons/etc
- close submenus on sidebar automatically?
- hide the plus button when editting? Make it into an X when the form is open. Animate it?
- sidebar button should match style of the top navigation button
- sidebar file should be general, not project specific
- top bar should never go double-lined
- I need a better logo
- Document all php 'require' paths
- don't show html on datatables data
- Log out should intuitivly log you out of what you want it to
- I shouldn't have visibility on projects I'm not user of ¿? does that make sense?
- How do I manage CMS-wide users?
- No top menu tables on 'home'
- add side bar to login?
- show display name for projects
- SYNDICATION
- make sure edit and trash icons are always level
- page creator
	- actual page should define $url, require config, require page.php
	- create page.php as a simbolic link to /src/page.php 
- verify session on pages? would need to be able to mark pages as private as well

### Running php dev server 
	./start.sh - will start mysql and php dev server
	you should have mysql installed on your mac and '/usr/local/mysql/bin' in your PATH

### JS yaml library used
https://www.npmjs.com/package/yamljs

### When you run 'sudo ./start'
- the start script runs, check usage with -h

#### start.sh
- checks is web port is free
- checks if db port is listening and restarts db bif not
- creates start_settings.inc.php
- starts the php server and opens the url for you

### When you click 'Submit'
- index.php sends the form data to itself

#### index.php
- runs ./build_pre.sh projectName db_host db_user db_pass imageTables
- writes projectName/admin/config.inc.php
- writes projectName/projectName.yml
- writes projectName/projectName.sql
- runs ./build_post.sh projectName db_host db_user db_pass imageTables

#### build_pre.sh
- recreates folder projects/projectName/admin/uploads
- writes src/* files into it
- writes projects/projectName/admin/db_connection.inc.php
- writes projects/projectName/admin/session.inc.php
- creates individual upload folders for tables with files

#### build_post.sh
- runs projects/projectName/projectName.sql against db

### Notes
- To change mysql port: sudo vi /Library/LaunchDaemons/com.oracle.oss.mysql.mysqld.plist
