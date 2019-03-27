# framework 1.5
### TODO
- Note: handy command: git ls-files | xargs wc -l
- extending the projects from within, issuing create table statements?
- should access to reference imply access to referenced column?
- encript passwords in db
- make sure we get feedback if db couldn´t be created.
- make sure we get feedback on any sql errors.
- address the todo's on index.php
- Figure out a way to create content that is not published, sucerely (possibly the where parameter on the read service)
- Revise logging, currently only using error_log
- Should the menu really be generated by the backend?
- improve documentation
	- document ./start parameters
	- Document all php 'require' paths
	- Extentions
		- Front
			- Available hooks
			- Displaying HTML on page (display = 'html';) (see page.r.php and theme.r.php)
		- Back
			- Available hooks
	- Creating portlets and pages
	- Themes
		- can create a theme within another
	- YAML conf file
		- Specify valid file extensions
		- Permissions
			- defaults
			- dash vs 'dot star'
		- _show fields
		- select: multi
		- select: tables (requires no type attribute)
		- booleans
	- Document calls to cruds
- I shouldn't have visibility on projects I'm not user of ¿? does that make sense?
- SYNDICATION
- check if page and theme url availability need adjustments due to case sensitivity
- verify session on pages from page table? would need to be able to mark pages as private as well
- Import/Export
	- maker_mike Import with drag and drop
	- maker_mike Export should download a yaml file (or create an entire maker mike file with all files and whole db zipped in?)
	- deletion of project could just export them to a dedicated folder
- I need a better logo
- use unlimited column types (will affect db creation) for html
- I should allow for back and forward browser button functionality at index.php (you can't currently reach the same tab you were on)
- create copy on edit
- only preview or don't show even don't show html field on datatable.
- should file size limit be a per project setting? (do I need per project settings?) same thing with input height threadholds
- create test suite
- incorporate OAuth 2.0 for Google accounts
- hide project specific disabled sidebar links 
- front end project config builder?
- use websockets?
- confirm project deletions
- why is sidebar_projects being called twice?
- IMPORTANT:
- containerize this
	- choose between
		- Using RUN git clone ... in a Dockerfile and build the image each time the source code changes.
		- Get the source code to the host and use COPY . /whatever in the Dockerfile.
		- Get the source code to the host and use docker run -v $(pwd):/whatever/
	- get the logs (2 sources)
		- tail -f /var/log/apache2/php_errors.log
		- the normal ./start
	- secure viewer.php, if it's not
- jQuery extension client library
	- client demonstration tool
	- some kind of discovery?
	- constructor (set base endpoint and query parameters in common (table and project))
	- maybe think of a more object oriented approach?
	- endpoints		
		- Authentication
			- get
			- set
		- Create (post)
			- body parameters
				-  id
				- columns[]
		- Read (get)
			- query parameters
				- show
				- only
				- id
				- where
				- equals
				- columns
		- Update (post)
			- body parameters
				-  id
				- columns[]
		- Delete (post)
			- body parameters
				-  id

#### Recreating the maker_mike project
- note you will loose all project table entries on the maker tab, so projects will be in a limbo as the dabases will continue to exist
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
