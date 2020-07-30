  <?php
    require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');
    require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/lib/moodlelib.php');
    require_login();
    // notify CR when a process is created
    function notify_cr_pc($from = '', $demandStart, $startA, $startB, $processEnd, $studyProgram)
    {
        global $DB, $CFG;
        if (empty($from)) {
            $from = 'DHBW Mannheim';
        }
        $sql = "SELECT cr.email , us.firstname , us.username , cr.mdl_user_id , co.classification
                FROM {dg_company_representative} AS cr
                INNER JOIN {user} AS us ON cr.mdl_user_id = us.id
                INNER JOIN {dg_company} AS co ON cr.compnay_id = co.id";
        $users = $DB->get_records_sql($sql);


        $title = 'New Student Registration Process';
        foreach ($users as $cr) {

            $user = new stdClass();
            $user->id = $cr->mdl_user_id;
            $user->email = $cr->email;
            $user->username = $cr->username;
            $reservationDate = '';
            if ($cr->classification == 'A') {
                $reservationDate = $startA;
            } else {
                $reservationDate = $startB;
            }
            $form = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><style></style></head>';
            $form .= '<body><span style="font-family: Arial; font-size: 10pt;">Dear ' . $cr->firstname . ',<br>';
            $form .= '<br>The DHBW Mannheim opened a new online student reservation process for the study program: <b>' . $studyProgram . ' </b>. In case you are planning to register students at the DHBW Mannheim for the previously mentioned study program, please login to our <a href="' . $CFG->wwwroot . '">Moodle website</a> and kindly add your planned demand of study places. After demand planning phase ends, the DHBW Mannheim will set the capacity for the study program accordinglly.';
            $form .= 'Please be aware of the following dates:<br>';
            $form .= '-------------------------------------------------------------------------------<br> ';
            $form .= '<ul><li>Demand Planning Start Date: <strong>' . $demandStart . ' </strong></li>';
            $form .= '<tr><li>Seats Reservation Start Date: <strong>' . $reservationDate . '</strong></li>';
            $form .= '<tr><li>Registration Deadline: <strong>' . $processEnd . '</strong></li></ul>';
            $form .= '-------------------------------------------------------------------------------<br>';
            $form .= '<b>Hint: </b>Each reservation for a study place is considered binding for 4 weeks. In case the status of the relevant contract for the study place was not set to <strong>Singed</strong> or <strong>Sent</strong>, the DHBW have all the rights to cancel the reservation for the reserved study place.<br>';
            $form .= '<br>Kind regards<br>DHBW Mannheim';
            email_to_user($user, $from, $title, $form);
        }
    }
