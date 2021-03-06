<?php

require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->dirroot.'/mod/facetoface/lib.php');

class mod_facetoface_mod_form extends moodleform_mod {

    function definition()
    {
        global $CFG;

        $mform =& $this->_form;

        // GENERAL
        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('text', 'name', get_string('name'), array('size' => '64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');

        $this->add_intro_editor(true);

        $mform->addElement('text', 'thirdparty', get_string('thirdpartyemailaddress', 'facetoface'), array('size' => '64'));
        $mform->setType('thirdparty', PARAM_EMAIL);
        $mform->addHelpButton('thirdparty', 'thirdpartyemailaddress', 'facetoface');

        $mform->addElement('checkbox', 'thirdpartywaitlist', get_string('thirdpartywaitlist', 'facetoface'));
        $mform->addHelpButton('thirdpartywaitlist', 'thirdpartywaitlist', 'facetoface');

        $display = array();
        for ($i=0; $i<=18; $i += 2) {
            $display[$i] = $i;
        }
        $mform->addElement('select', 'display', get_string('sessionsoncoursepage', 'facetoface'), $display);
        $mform->setDefault('display', 6);
        $mform->addHelpButton('display', 'sessionsoncoursepage', 'facetoface');

        $mform->addElement('checkbox', 'approvalreqd', get_string('approvalreqd', 'facetoface'));
        $mform->addHelpButton('approvalreqd', 'approvalreqd', 'facetoface');

        $mform->addElement('header', 'calendaroptions', get_string('calendaroptions', 'facetoface'));

        $calendarOptions = array(
            F2F_CAL_NONE    =>  get_string('none'),
            F2F_CAL_COURSE  =>  get_string('course'),
            F2F_CAL_SITE    =>  get_string('site')
        );
        $mform->addElement('select', 'showoncalendar', get_string('showoncalendar', 'facetoface'), $calendarOptions);
        $mform->setDefault('showoncalendar', F2F_CAL_COURSE);
        $mform->addHelpButton('showoncalendar', 'showoncalendar', 'facetoface');

        $mform->addElement('advcheckbox', 'usercalentry', get_string('usercalentry', 'facetoface'));
        $mform->setDefault('usercalentry', true);
        $mform->addHelpButton('usercalentry', 'usercalentry', 'facetoface');

        $mform->addElement('text', 'shortname', get_string('shortname'), array('size' => 32, 'maxlength' => 32));
        $mform->setType('shortname', PARAM_TEXT);
        $mform->addHelpButton('shortname', 'shortname', 'facetoface');
        $mform->addRule('shortname', null, 'maxlength', 32);

        // REQUEST MESSAGE
        $mform->addElement('header', 'request', get_string('requestmessage', 'facetoface'));
        $mform->addHelpButton('request', 'requestmessage', 'facetoface');

        $mform->addElement('text', 'requestsubject', get_string('email:subject', 'facetoface'), array('size' => '55'));
        $mform->setType('requestsubject', PARAM_TEXT);
        $mform->setDefault('requestsubject', get_string('setting:defaultrequestsubjectdefault', 'facetoface'));
        $mform->disabledIf('requestsubject', 'approvalreqd');

        $mform->addElement('textarea', 'requestmessage', get_string('email:message', 'facetoface'), 'wrap="virtual" rows="15" cols="70"');
        $mform->setDefault('requestmessage', get_string('setting:defaultrequestmessagedefault', 'facetoface'));
        $mform->disabledIf('requestmessage', 'approvalreqd');

        $mform->addElement('textarea', 'requestinstrmngr', get_string('email:instrmngr', 'facetoface'), 'wrap="virtual" rows="10" cols="70"');
        $mform->setDefault('requestinstrmngr', get_string('setting:defaultrequestinstrmngrdefault', 'facetoface'));
        $mform->disabledIf('requestinstrmngr', 'approvalreqd');

        // CONFIRMATION MESSAGE
        $mform->addElement('header', 'confirmation', get_string('confirmationmessage', 'facetoface'));
        $mform->addHelpButton('confirmation', 'confirmationmessage', 'facetoface');

        $mform->addElement('text', 'confirmationsubject', get_string('email:subject', 'facetoface'), array('size' => '55'));
        $mform->setType('confirmationsubject', PARAM_TEXT);
        $mform->setDefault('confirmationsubject', get_string('setting:defaultconfirmationsubjectdefault', 'facetoface'));

        $mform->addElement('textarea', 'confirmationmessage', get_string('email:message', 'facetoface'), 'wrap="virtual" rows="15" cols="70"');
        $mform->setDefault('confirmationmessage', get_string('setting:defaultconfirmationmessagedefault', 'facetoface'));

        $mform->addElement('checkbox', 'emailmanagerconfirmation', get_string('emailmanager', 'facetoface'));
        $mform->addHelpButton('emailmanagerconfirmation', 'emailmanagerconfirmation', 'facetoface');

        $mform->addElement('textarea', 'confirmationinstrmngr', get_string('email:instrmngr', 'facetoface'), 'wrap="virtual" rows="4" cols="70"');
        $mform->addHelpButton('confirmationinstrmngr', 'confirmationinstrmngr', 'facetoface');
        $mform->disabledIf('confirmationinstrmngr', 'emailmanagerconfirmation');
        $mform->setDefault('confirmationinstrmngr', get_string('setting:defaultconfirmationinstrmngrdefault', 'facetoface'));

        // REMINDER MESSAGE
        $mform->addElement('header', 'reminder', get_string('remindermessage', 'facetoface'));
        $mform->addHelpButton('reminder', 'remindermessage', 'facetoface');

        $mform->addElement('text', 'remindersubject', get_string('email:subject', 'facetoface'), array('size' => '55'));
        $mform->setType('remindersubject', PARAM_TEXT);
        $mform->setDefault('remindersubject', get_string('setting:defaultremindersubjectdefault', 'facetoface'));

        $mform->addElement('textarea', 'remindermessage', get_string('email:message', 'facetoface'), 'wrap="virtual" rows="15" cols="70"');
        $mform->setDefault('remindermessage', get_string('setting:defaultremindermessagedefault', 'facetoface'));

        $mform->addElement('checkbox', 'emailmanagerreminder', get_string('emailmanager', 'facetoface'));
        $mform->addHelpButton('emailmanagerreminder', 'emailmanagerreminder', 'facetoface');

        $mform->addElement('textarea', 'reminderinstrmngr', get_string('email:instrmngr', 'facetoface'), 'wrap="virtual" rows="4" cols="70"');
        $mform->addHelpButton('reminderinstrmngr', 'reminderinstrmngr', 'facetoface');
        $mform->disabledIf('reminderinstrmngr', 'emailmanagerreminder');
        $mform->setDefault('reminderinstrmngr', get_string('setting:defaultreminderinstrmngrdefault', 'facetoface'));

        $reminderperiod = array();
        for ($i=1; $i<=20; $i += 1) {
            $reminderperiod[$i] = $i;
        }
        $mform->addElement('select', 'reminderperiod', get_string('reminderperiod', 'facetoface'), $reminderperiod);
        $mform->setDefault('reminderperiod', 2);
        $mform->addHelpButton('reminderperiod', 'reminderperiod', 'facetoface');

        // WAITLISTED MESSAGE
        $mform->addElement('header', 'waitlisted', get_string('waitlistedmessage', 'facetoface'));
        $mform->addHelpButton('waitlisted', 'waitlistedmessage', 'facetoface');

        $mform->addElement('text', 'waitlistedsubject', get_string('email:subject', 'facetoface'), array('size' => '55'));
        $mform->setType('waitlistedsubject', PARAM_TEXT);
        $mform->setDefault('waitlistedsubject', get_string('setting:defaultwaitlistedsubjectdefault', 'facetoface'));

        $mform->addElement('textarea', 'waitlistedmessage', get_string('email:message', 'facetoface'), 'wrap="virtual" rows="15" cols="70"');
        $mform->setDefault('waitlistedmessage', get_string('setting:defaultwaitlistedmessagedefault', 'facetoface'));

        $mform->addElement('checkbox', 'disableautoenroll', get_string('disableautoenroll','facetoface'));
        $mform->addHelpButton('disableautoenroll', 'disableautoenroll', 'facetoface');
        
        $disableperiod = array();
        for ($i=0; $i<=60; $i += 1) {
                $disableperiod[$i] = $i;
        }

        $mform->addElement('select', 'disablewithindays', get_string('disablewithindays', 'facetoface'),$disableperiod);
        $mform->setDefault('disablewithindays', 0);
        $mform->disabledIf('disablewithindays', 'disableautoenroll');
        $mform->addHelpButton('disablewithindays', 'disablewithindays', 'facetoface');

        // CANCELLATION MESSAGE
        $mform->addElement('header', 'cancellation', get_string('cancellationmessage', 'facetoface'));
        $mform->addHelpButton('cancellation', 'cancellationmessage', 'facetoface');

        $mform->addElement('text', 'cancellationsubject', get_string('email:subject', 'facetoface'), array('size' => '55'));
        $mform->setType('cancellationsubject', PARAM_TEXT);
        $mform->setDefault('cancellationsubject', get_string('setting:defaultcancellationsubjectdefault', 'facetoface'));

        $mform->addElement('textarea', 'cancellationmessage', get_string('email:message', 'facetoface'), 'wrap="virtual" rows="15" cols="70"');
        $mform->setDefault('cancellationmessage', get_string('setting:defaultcancellationmessagedefault', 'facetoface'));

        $mform->addElement('checkbox', 'emailmanagercancellation', get_string('emailmanager', 'facetoface'));
        $mform->addHelpButton('emailmanagercancellation', 'emailmanagercancellation', 'facetoface');

        $mform->addElement('textarea', 'cancellationinstrmngr', get_string('email:instrmngr', 'facetoface'), 'wrap="virtual" rows="4" cols="70"');
        $mform->addHelpButton('cancellationinstrmngr', 'cancellationinstrmngr', 'facetoface');
        $mform->disabledIf('cancellationinstrmngr', 'emailmanagercancellation');
        $mform->setDefault('cancellationinstrmngr', get_string('setting:defaultcancellationinstrmngrdefault', 'facetoface'));

        $features = new stdClass;
        $features->groups = false;
        $features->groupings = false;
        $features->groupmembersonly = false;
        $features->outcomes = false;
        $features->gradecat = false;
        $features->idnumber = true;
        $this->standard_coursemodule_elements($features);

        $this->add_action_buttons();
    }

    function data_preprocessing(&$default_values)
    {
        // Fix manager emails
        if (empty($default_values['confirmationinstrmngr'])) {
            $default_values['confirmationinstrmngr'] = null;
        }
        else {
            $default_values['emailmanagerconfirmation'] = 1;
        }

        if (empty($default_values['reminderinstrmngr'])) {
            $default_values['reminderinstrmngr'] = null;
        }
        else {
            $default_values['emailmanagerreminder'] = 1;
        }

        if (empty($default_values['cancellationinstrmngr'])) {
            $default_values['cancellationinstrmngr'] = null;
        }
        else {
            $default_values['emailmanagercancellation'] = 1;
        }
    }
}
