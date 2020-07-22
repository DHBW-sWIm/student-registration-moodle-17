# student_registration process for the DHBW Mannheim 

- Setup CR role: 
- go to Site administration -> users -> permissions ->define roles -> add new role (here is important) just click continue -> ( short name & custom full name = companyrepresentative , select check box of context type = System) and finally scroll down and write in the filter the following :
local/student_registration:cr
then select check box allow and save

after this install the plugin from a zip file; Hint: rename the zip file to student_registration otherwise installation will crash.

if you want to check this create 3 new users: ( manager, company rep A, company rep B )
- assign a manager role (site administration -> users -> permissions -> assign system role ) to the the first user
- assign the company representative role to other users

whenever you login from the CR user you'll notice a different view


currently UI tiles are generated directly from the database therefore you must execute the following SQL code: 

INSERT INTO `mdl_sr_management_tiles` (`id`, `title`, `button_name`, `button_url`, `button_icon`, `list_element_1`, `list_element_2`, `list_element_3`, `list_element_4`, `color`, `tile_order`, `plugin`, `user_id`, `task_path`, `function`, `moodle_capability`, `element_1_link`, `element_2_link`, `element_3_link`, `element_4_link`) VALUES
(1, 'Student Registration', 'Manage', '/local/student_registration/views/Menu.php', 'fa fa-user', '#1', '#2', '#3', '#4', 'danger', 1, 'Management Dashboard', 2, 'dashboard_lib.php', 'get_tasks_md', 'local/management_dashbaord:view', '/local/student_registration/views/ST_process/ST_prcess_overview.php', NULL, NULL, NULL),
(2, 'Scientific Paper', 'Manage', '/local/student_registration/index.php', 'fa fa-user', 'Assign Supervisors', '', '', '', 'danger', 2, 'Management Dashboard', 2, '', NULL, 'local/management_dashbaord:view', NULL, NULL, NULL, NULL),
(4, 'Student Registration', 'Manage', '/local/student_registration/views/ST_process/ST_prcess_overview.php', 'fa fa-user', '#5', '', '', '', 'danger', 1, 'Student Registration', 2, '../dashboard_lib.php', 'get_tasks_md', 'local/student_registration:manage', NULL, NULL, NULL, NULL),
(5, 'Capacity Planning', 'Manage', '/local/student_registration/views/ST_process/ST_active_process_Ma_CP.php', 'fa fa-user', '', '', '', '', 'danger', 2, 'Student Registration', 2, '', NULL, 'local/student_registration:manage', NULL, NULL, NULL, NULL),
(6, 'Course Management', 'Manage', '/local/student_registration/views/ST_process/ST_active_process_Ma_CC.php', 'fa fa-user', '', '', '', '', 'danger', 3, 'Student Registration', 2, '', NULL, 'local/student_registration:manage', NULL, NULL, NULL, NULL),
(7, 'Settings for STRE', 'Edit', '/local/student_registration/views/Settings.php', 'fa fa-globe', '', '', '', '', 'danger', 6, 'Student Registration', 2, '', '', 'local/student_registration:manage', '', '', '', ''),
(8, 'Lecturer recruitment', 'Manage', '/local/lecrec/index.php', 'fa fa-user', 'Help center access', '', '', '', 'danger', 3, 'Management Dashboard', 2, '', '', 'local/management_dashbaord:view', NULL, NULL, NULL, NULL),
(9, 'CRM', 'Go to V-Tiger', 'https://google.com#external', 'fa fa-globe', '', '', '', '', 'danger', 4, 'Management Dashboard', 2, '', NULL, 'local/management_dashbaord:view', NULL, NULL, NULL, NULL),
(10, 'Study Programs Management', 'Manage', '/local/student_registration/views/SP_master_data/SP_Master_Data.php', 'fa fa-user', 'Add new Study Programs', 'Adjust existing Study Programs', '', '', 'danger', 4, 'Student Registration', 2, '', '', 'local/student_registration:manage', '', '', '', ''),
(11, 'Student Registration', 'Manage', '/local/student_registration/views/ST_process/ST_active_process_CR_ST.php', 'fa fa-user', 'Register Students', '', '', '', 'danger', 2, 'Student Registration', 2, '', NULL, 'local/student_registration:cr', NULL, NULL, NULL, NULL),
(13, 'Demand Planning', 'Manage', '/local/student_registration/views/ST_process/ST_active_process_CR_DP.php', 'fa fa-user', 'Submit your Demand Planning', NULL, NULL, NULL, 'danger', 1, 'Student Registration', 2, NULL, NULL, 'local/student_registration:cr', NULL, NULL, NULL, NULL),
(14, 'Reporting', 'View', '/local/student_registration/views/ST_Reporting/ST_reporting_main.php', 'fa fa-bar-chart', '', '', '', '', 'danger', 5, 'Student Registration', 2, '', '', 'local/student_registration:manage', '', '', '', '');


Link: https://student-registration.swimdhbw.de/
-----------------------------------------------
Admin User name: user
Admin User password: password
-----------------------------------------------
manager user name: manager
manager user password: Manager123_
-----------------------------------------------
manager user name: manager2
manager user password: Manager123_
-----------------------------------------------
Company representative A user name: companya
password: CompanyA123_
-----------------------------------------------
Company representative B user name: companyb
password: CompanyB123_
-----------------------------------------------
             ||    Important    || 
-----------------------------------------------
- Add a new Study Programs (WI), then add 
  relevant study fields (Sales & Consulting)
- You will be able now to create an ST process
- You can update the process at any time 
- To enter the dashbaord settings, you need
  either an admin user or assign the following
  permission to any Role: 
  (local/management_dashbaord:edit)
  Steps: 
	1- Go to site administration
	2- Enter user tab
	3- Permissions --> define role
	4- Either define a new role or edit an 
	   exsiting role. Hint: in case you 
	   define a new role, the context must 
	   be set to SYSTEM
	5- Click on edit and filter the 
	   capabilities to select the permission
- To assign a permission for company 
  representatives, just follow the above-mentioned
  procedures and assign the following permission
  (local/student_registration:cr)
- The Admin user is the root user and has all
  permissions, therefore; it will see a tiles 
-----------------------------------------------
               ||    Info    || 
-----------------------------------------------
This plugin is still under development and some
featuers are not complete such as Email 
notificaiton (initial template ist sent and cought
by mail hog at 
https://mailhog.student-registration.swimdhbw.de/)
Reporting dashboard is still not complete (only
process reporting is available)
Records in the reporting dashboard are shown to
all users whos have permission to access the 
dashboard, meanwhile, managing these records 
(ST processes) can only be done by the user 
who created them.
To see records on the database: 
https://adminer.student-registration.swimdhbw.de/
System: MySQL
server: student-registration-swim-process-moodle-mariadb
username: bn_moodle
Password: swim-access
database: bitnami_moodle
-----------------------------------------------
