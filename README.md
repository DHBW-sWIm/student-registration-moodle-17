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

INSERT INTO `moodle`.`mdl_sr_management_tiles`
(`title`,
`button_name`,
`button_url`,
`button_icon`,
`list_element_1`,
`list_element_2`,
`list_element_3`,
`list_element_4`,
`color`,
`tile_order`,
`plugin`)
VALUES
(
'Student Registration',
'Manage',
'/local/student_registration/views/Menu.php',
'fa fa-user',
'dashboard_lib.php',
'dashboard_lib.php',
'dashboard_lib.php',
'dashboard_lib.php',
'danger',
1,
'Management Dashboard');   

INSERT INTO `moodle`.`mdl_sr_management_tiles`
(`title`,
`button_name`,
`button_url`,
`button_icon`,
`list_element_1`,
`list_element_2`,
`list_element_3`,
`list_element_4`,
`color`,
`tile_order`,
`plugin`)
VALUES
(
'Scientific Paper',
'Manage',
'/local/student_registration/index.php',
'fa fa-user',
'Assign Supervisors',
'',
'',
'',
'danger',
2,
'Management Dashboard');   

INSERT INTO `moodle`.`mdl_sr_management_tiles`
(`title`,
`button_name`,
`button_url`,
`button_icon`,
`list_element_1`,
`list_element_2`,
`list_element_3`,
`list_element_4`,
`color`,
`tile_order`,
`plugin`)
VALUES
(
'Dashboard Settings',
'Settings ',
'/local/student_registration/Dashboard_Settings.php',
'fa fa-globe',
'Create new tiles',
'Append Tasks',
'',
'',
'danger',
100,
'Management Dashboard');   

INSERT INTO `moodle`.`mdl_sr_management_tiles`
(`title`,
`button_name`,
`button_url`,
`button_icon`,
`list_element_1`,
`list_element_2`,
`list_element_3`,
`list_element_4`,
`color`,
`tile_order`,
`plugin`)
VALUES
(
'Student Registration',
'Manage',
'/local/student_registration/views/ST_process/ST_prcess_overview.php',
'fa fa-user',
'Create a new registration process',
'Manage active processes',
'',
'',
'danger',
1,
'Student Registration');  
INSERT INTO `moodle`.`mdl_sr_management_tiles`
(`title`,
`button_name`,
`button_url`,
`button_icon`,
`list_element_1`,
`list_element_2`,
`list_element_3`,
`list_element_4`,
`color`,
`tile_order`,
`plugin`)
VALUES
(
'Capacity Planning',
'Manage',
'/local/student_registration/views/ST_process/ST_active_process_Ma_CP.php',
'fa fa-user',
'Manage Capacity Planning',
'',
'',
'',
'danger',
2,
'Student Registration');    

INSERT INTO `moodle`.`mdl_sr_management_tiles`
(`title`,
`button_name`,
`button_url`,
`button_icon`,
`list_element_1`,
`list_element_2`,
`list_element_3`,
`list_element_4`,
`color`,
`tile_order`,
`plugin`)
VALUES
(
'Course Management',
'Manage',
'/local/student_registration/views/ST_process/ST_active_process_Ma_CC.php',
'fa fa-user',
'Create new courses',
'Assign students to courses',
'',
'',
'danger',
3,
'Student Registration'); 

INSERT INTO `moodle`.`mdl_sr_management_tiles`
(`title`,
`button_name`,
`button_url`,
`button_icon`,
`list_element_1`,
`list_element_2`,
`list_element_3`,
`list_element_4`,
`color`,
`tile_order`,
`plugin`)
VALUES
(
'Settings for STRE',
'Edit',
'/local/student_registration/views/Settings.php',
'fa fa-globe',
'Create new courses',
'',
'',
'',
'danger',
3,
'Student Registration');  
