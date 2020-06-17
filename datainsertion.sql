    INSERT INTO `moodle`.`mdl_sr_study_programs` (`id`, `study_program_name`, `description`, `valid_from`, `valid_to`, `old`) VALUES 
    (NULL, 'Business Informatics', NULL, '20190801', '20270801', '0'), (NULL, 'Business Adminstration', NULL, '20190801', '20270801', '0');


    INSERT INTO `moodle`.`mdl_sr_process` (`id`, `program_name`, `start_date`, `end_date`, `timecreated`, `timemodified`, `start_date_for_a`, `start_date_for_b`, `closed`, `director_id`, `sr_study_programs_id`, `usermodified`) VALUES
    (1, 'Business Informatics', '2020-10-01 12:00:00', '2021-08-01 12:00:00', NULL, NULL, '2021-03-01 12:00:00', '2021-04-01 12:00:00', 0, 2, 1, 2),
    (2, 'Business Administration', '2020-10-01 12:00:00', '2021-08-01 12:00:00', NULL, NULL, '2021-03-01 12:00:00', '2021-04-01 12:00:00', 0, 2, 1, 2);


    INSERT INTO `moodle`.`mdl_sr_study_fields`
    (
    `study_field_name`,
    `description`,
    `old`,
    `sr_study_programs_id`)
    VALUES
    (
    'Software Engineering',
    '',
    0,
    1);

    INSERT INTO `moodle`.`mdl_sr_study_fields`
    (
    `study_field_name`,
    `description`,
    `old`,
    `sr_study_programs_id`)
    VALUES
    (
    'Sales and Consulting',
    '',
    0,
    1);

    INSERT INTO `moodle`.`mdl_sr_study_fields`
    (
    `study_field_name`,
    `description`,
    `old`,
    `sr_study_programs_id`)
    VALUES
    (
    'Application Management',
    '',
    0,
    1);

    INSERT INTO `moodle`.`mdl_sr_study_fields`
    (
    `study_field_name`,
    `description`,
    `old`,
    `sr_study_programs_id`)
    VALUES
    (
    'Data Science',
    '',
    0,
    1);

    INSERT INTO `moodle`.`mdl_sr_study_fields`
    (
    `study_field_name`,
    `description`,
    `old`,
    `sr_study_programs_id`)
    VALUES
    (
    'E-Government',
    '',
    0,
    1);


    INSERT INTO `moodle`.`mdl_sr_study_fields`
    (
    `study_field_name`,
    `description`,
    `old`,
    `sr_study_programs_id`)
    VALUES
    (
    'E-Health',
    '',
    0,
    1);

    INSERT INTO `moodle`.`mdl_sr_study_fields`
    (
    `study_field_name`,
    `description`,
    `old`,
    `sr_study_programs_id`)
    VALUES
    (
    'International Management for Business and Information Technology',
    '',
    0,
    1);

    INSERT INTO `moodle`.`mdl_sr_study_fields`
    (
    `study_field_name`,
    `description`,
    `old`,
    `sr_study_programs_id`)
    VALUES
    (
    'Bank',
    '',
    0,
    2);

    INSERT INTO `moodle`.`mdl_sr_study_fields`
    (
    `study_field_name`,
    `description`,
    `old`,
    `sr_study_programs_id`)
    VALUES
    (
    'Digital Business Management',
    '',
    0,
    2);

    INSERT INTO `moodle`.`mdl_sr_study_fields`
    (
    `study_field_name`,
    `description`,
    `old`,
    `sr_study_programs_id`)
    VALUES
    (
    'Financial services',
    '',
    0,
    2);

    INSERT INTO `moodle`.`mdl_sr_study_fields`
    (
    `study_field_name`,
    `description`,
    `old`,
    `sr_study_programs_id`)
    VALUES
    (
    'Health Management',
    '',
    0,
    2);

    INSERT INTO `moodle`.`mdl_sr_study_fields`
    (
    `study_field_name`,
    `description`,
    `old`,
    `sr_study_programs_id`)
    VALUES
    (
    'Trade',
    '',
    0,
    2);


    INSERT INTO `moodle`.`mdl_sr_study_fields`
    (
    `study_field_name`,
    `description`,
    `old`,
    `sr_study_programs_id`)
    VALUES
    (
    'Real-estate Industry',
    '',
    0,
    2);


    INSERT INTO `moodle`.`mdl_sr_study_fields`
    (
    `study_field_name`,
    `description`,
    `old`,
    `sr_study_programs_id`)
    VALUES
    (
    'Industry',
    '',
    0,
    2);

    INSERT INTO `moodle`.`mdl_sr_study_fields`
    (
    `study_field_name`,
    `description`,
    `old`,
    `sr_study_programs_id`)
    VALUES
    (
    'International Business',
    '',
    0,
    2);


    INSERT INTO `moodle`.`mdl_sr_study_fields`
    (
    `study_field_name`,
    `description`,
    `old`,
    `sr_study_programs_id`)
    VALUES
    (
    'Marketing Management',
    '',
    0,
    2);


    INSERT INTO `moodle`.`mdl_sr_study_fields`
    (
    `study_field_name`,
    `description`,
    `old`,
    `sr_study_programs_id`)
    VALUES
    (
    'Fair Congress and Event Management',
    '',
    0,
    2);


    INSERT INTO `moodle`.`mdl_sr_study_fields`
    (
    `study_field_name`,
    `description`,
    `old`,
    `sr_study_programs_id`)
    VALUES
    (
    'Public economy',
    '',
    0,
    2);


    INSERT INTO `moodle`.`mdl_sr_study_fields`
    (
    `study_field_name`,
    `description`,
    `old`,
    `sr_study_programs_id`)
    VALUES
    (
    'Forwarding, transport and logistics',
    '',
    0,
    2);

    INSERT INTO `moodle`.`mdl_sr_study_fields`
    (
    `study_field_name`,
    `description`,
    `old`,
    `sr_study_programs_id`)
    VALUES
    (
    'Insurance',
    '',
    0,
    2);

    INSERT INTO `moodle`.`mdl_sr_partner_compnay`
    (
    `company_name`,
    `classification`,
    `contact_information`)
    VALUES
    (
    'SAP SE',
    1,
    '');


    INSERT INTO `moodle`.`mdl_sr_partner_compnay`
    (
    `company_name`,
    `classification`,
    `contact_information`)
    VALUES
    (
    'IBM',
    1,
    '');


    INSERT INTO `moodle`.`mdl_sr_partner_compnay`
    (
    `company_name`,
    `classification`,
    `contact_information`)
    VALUES
    (
    'Aldi',
    1,
    '');


    INSERT INTO `moodle`.`mdl_sr_partner_compnay`
    (
    `company_name`,
    `classification`,
    `contact_information`)
    VALUES
    (
    'DHL',
    0,
    '');



    INSERT INTO `moodle`.`mdl_sr_company_representative`
    (
    `first_name`,
    `last_name`,
    `email`,
    `phone`,
    `sr_partner_compnay_id`
    )
    VALUES
    (
    'Simon',
    'CR',
    'simon.cr@sap.com',
    0176012548,
    1
    );


    INSERT INTO `moodle`.`mdl_sr_company_representative`
    (
    `first_name`,
    `last_name`,
    `email`,
    `phone`,
    `sr_partner_compnay_id`
    )
    VALUES
    (
    'Sam',
    'CR',
    'Sam.cr@ibm.com',
    0176012548,
    2
    );


    INSERT INTO `moodle`.`mdl_sr_company_representative`
    (
    `first_name`,
    `last_name`,
    `email`,
    `phone`,
    `sr_partner_compnay_id`
    )
    VALUES
    (
    'Florian',
    'CR',
    'Florian.cr@aldi.com',
    0176012548,
    3
    );


    INSERT INTO `moodle`.`mdl_sr_company_representative`
    (
    `first_name`,
    `last_name`,
    `email`,
    `phone`,
    `sr_partner_compnay_id`
    )
    VALUES
    (
    'Jan',
    'CR',
    'Jan.cr@dhl.com',
    0176012548,
    4
    );


