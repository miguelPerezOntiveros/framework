# framework 1.5
### TODO
- extending the projects from within, issuing create table statements?
- I should allow for back and forward browser button functionality at index.php (you can't currently reach the same tab you were on)
- reverse engineer sql dumps to my yaml syntax?
- should access to reference imply access to referenced column?
- encript passwords in db
- make sure no sql injection is possible, use pdo's
- make sure we get feedback if db couldn´t be created.
- make sure we get feedback on any sql errors.
- add 'show' prop to individual columns, I can test that out with portlet tables
- address the todo's on index.php
- Figure out a way to create content that is not published, sucerely (possibly the where parameter on the read service)
- Change over to mysqli
- Revise logging, currently only using error_log
- Should the menu really be generated by the backend?
- You can notice the sidebar gets split while the animation is playing
- revise footer, add links/icons/etc
- close submenus on sidebar automatically?
- hide the plus button when editting? Make it into an X when the form is open. Animate it?
- sidebar button should match style of the top navigation button
- improve documentation
	- Document all php 'require' paths
	- Document extention files and hooks for front and backend
- Log out should intuitivly log you out of what you want it to
- I shouldn't have visibility on projects I'm not user of ¿? does that make sense?
- How do I manage CMS-wide users?
- SYNDICATION
- verify session on pages from page table? would need to be able to mark pages as private as well
- should restrict from making pages under admin or using double dots
- maker_mike Import with drag and drop
- maker_mike Export should download a yaml file
- I need a better logo
- should find a friendlier and more secure way for adding in the query on portlets 
	- build service to get selected tables' columns for the query_columns multiple select field
- center form, issue on larger tables

- git ls-files | xargs wc -l

#### Recreating the maker_mike project
- you will loose all project table entries on the maker tab, so they will be in a limbo as the dabases will continue to exist
- run your yaml on the maker tab
- run these extra commands needed to enable the home page:
	- cd projects/maker_mike/admin/
	- sudo ln -s /Users/miguel/git/framework/src/maker_mike.home.php .
	- sudo mv maker_mike.home.php home.php
- delete the entry from the project table on the maker tab, the actual database will not be deleted

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
- recreates the project folder
- creates soft links to index, login and viewer, and to page table service extenders.
- creates individual upload folders for tables with files, and one for all service extensions

#### build_post.sh
- creates database

### Notes
- To change mysql port: sudo vi /Library/LaunchDaemons/com.oracle.oss.mysql.mysqld.plist
