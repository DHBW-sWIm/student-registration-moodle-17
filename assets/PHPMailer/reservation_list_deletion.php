  <?php
    require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');
    require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/lib/moodlelib.php');
    require_login();
    // notify CR when a a record was moved from waiting list to reseravtion list
    function notify_cr_delete_rl($from = '', $PID, $SFID, $CRID)
    {
        global $DB;
        if (empty($from)) {
            $from = 'DHBW Mannheim';
        }
        $ProName = $DB->get_record('sr_process', array('id' => $PID))->program_name;
        $FieldName = $DB->get_record('sr_study_fields', array('id' => $SFID))->study_field_name;
        $cr = $DB->get_record('dg_company_representative', array('mdl_user_id' => $CRID));
        $username = $DB->get_record('user', array('id' => $CRID))->username;
        $title = 'Reserved Study Place Cancelation';
        $user = new stdClass();
        $user->id = $cr->mdl_user_id;
        $user->email = $cr->email;
        $user->username = $username;

        $form = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><style></style></head>';
        $form .= '<body><span style="font-family: Arial; font-size: 10pt;">Dear ' . $cr->first_name . ',<br>';
        $form .= '<br>We confirm here by that we removed one of your records on Reservation List of the program: ' . $ProName . ' ' . $FieldName . '<br>';
        $form .= '<b>Hint: </b>Each reservation for a study place is considered binding for 4 weeks. In case the status of the relevant contract for the study place was not set to <strong>Singed</strong> or <strong>Sent</strong>, the DHBW have all the rights to cancel the reservation for the reserved study place.<br>';
        $form .= '<br>Kind regards<br>DHBW Mannheim';
        email_to_user($user, $from, $title, $form);
    }
