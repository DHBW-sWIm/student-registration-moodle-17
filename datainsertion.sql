DELETE FROM `mdl_sr_management_tiles`;
INSERT INTO `mdl_sr_management_tiles` (`
id`,
`title`,
`button_name`,
`button_url`,
`button_icon`,
`list_element_1`,
`list_element_2`,
`list_element_3
`, `list_element_4`, `color`, `tile_order`, `plugin`, `user_id`, `task_path`, `function`, `moodle_capability`) VALUES
(1, 'Student Registration', 'Manage', '/local/student_registration/views/Menu.php', 'fa fa-user', '#1', '#2', '#3', '#4', 'danger', 1, 'Management Dashboard', 2, 'dashboard_lib.php', 'get_tasks_md', 'local/management_dashbaord:view'),
(2, 'Scientific Paper', 'Manage', '/local/student_registration/index.php', 'fa fa-user', 'Assign Supervisors', '', '', '', 'danger', 2, 'Management Dashboard', 2, '', NULL, 'local/management_dashbaord:view'),
(4, 'Student Registration', 'Manage', '/local/student_registration/views/ST_process/ST_prcess_overview.php', 'fa fa-user', '#5', '', '', '', 'danger', 1, 'Student Registration', 2, '../dashboard_lib.php', 'get_tasks_md', 'local/student_registration:manage'),
(5, 'Capacity Planning', 'Manage', '/local/student_registration/views/ST_process/ST_active_process_Ma_CP.php', 'fa fa-user', '', '', '', '', 'danger', 2, 'Student Registration', 2, '', NULL, 'local/student_registration:manage'),
(6, 'Course Management', 'Manage', '/local/student_registration/views/ST_process/ST_active_process_Ma_CC.php', 'fa fa-user', '', '', '', '', 'danger', 3, 'Student Registration', 2, '', NULL, 'local/student_registration:manage'),
(7, 'Settings for STRE', 'Edit', '/local/student_registration/views/Settings.php', 'fa fa-globe', '', '', '', '', 'danger', 5, 'Student Registration', 2, '', NULL, 'local/student_registration:manage'),
(8, 'Lecturer recruitment', 'Manage', '/local/student_registration/index.php', 'fa fa-user', 'Help center access', '', '', '', 'danger', 3, 'Management Dashboard', 2, '', NULL, 'local/management_dashbaord:view'),
(9, 'CRM', 'Go to V-Tiger', 'https://google.com#external', 'fa fa-globe', '', '', '', '', 'danger', 4, 'Management Dashboard', 2, '', NULL, 'local/management_dashbaord:view'),
(10, 'Study Programs Management', 'Manage', '/local/student_registration/views/SP_master_data/SP_Master_Data.php', 'fa fa-user', 'Add new Study Programs', 'Adjust existing Study Programs', '', '', 'danger', 4, 'Student Registration', 2, '', NULL, 'local/student_registration:manage'),
(11, 'Student Registration', 'Manage', '/local/student_registration/views/ST_process/ST_active_process_CR_ST.php', 'fa fa-user', 'Register Students', '', '', '', 'danger', 2, 'Student Registration', 2, '', NULL, 'local/student_registration:cr'),
(13, 'Demand Planning', 'Manage', '/local/student_registration/views/ST_process/ST_active_process_CR_DP.php', 'fa fa-user', 'Submit your Demand Planning', NULL, NULL, NULL, 'danger', 1, 'Student Registration', 2, NULL, NULL, 'local/student_registration:cr');
