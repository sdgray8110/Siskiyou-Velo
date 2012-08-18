<?php
class emailHelper {
    public function __construct($id) {
        $this->id = $id;
        $this->formData = $this->get_form_data();
    }

    private function get_form_data() {
        $data = new stdClass();
        $settings = get_group('settings', $this->id);
        $recipients = get_group('recipient', $this->id);

        $data->header = get_the_title($this->id);
        $data->from = $settings[1]['settings_from_email_address'][1];
        $data->from = $settings[1]['settings_email_subject'][1];
        $data->recipients = $this->set_recipients($recipients);
        $data->subjects = $this->set_subjects($settings[1]['settings_subjects'][1]);

        return $data;
    }

    private function set_recipients($recipients) {
        $data = array();

        foreach($recipients as $recipient) {
            $item = new stdClass();

            $item->title = $recipient['recipient_title'][1];
            $item->name = $this->labelize($item->title);
            $item->description = $recipient['recipient_description'][1];
            $item->email = $recipient['recipient_email'][1];

            $data[] = $item;
        }

        return $data;
    }

    private function set_subjects($str) {
        $subjects = explode(',',$str);
        $arr = array();

        foreach ($subjects as $subject) {
            $item = new stdClass();

            $item->title = $subject;
            $item->name = $subject;

            $arr[] = $item;
        }

        return $arr;
    }

    private function labelize($str) {
        $lowerCase = strtolower($str);

        return str_replace(' ', '_', $lowerCase);
    }

    public function emailData($post) {
        $data = new stdClass();
        $data->subject = 'Sonic Boom Racing Contact: ' . $post['subject'];
        $data->name = $post['name'];
        $data->message = $post['message'];
        $data->department = $post['department'];
        $data->emailFrom = $post['email'];
        $data->emailTo = $this->email_recipient($data->department);

        return $data;
    }

    private function email_recipient($recipient) {
        $email = $this->formData->recipients[0]->email;

        foreach ($this->formData->recipients as $item) {
            if ($recipient == $item->name) {
                $email = $item->email;
            }
        }

        return $email;
    }
}