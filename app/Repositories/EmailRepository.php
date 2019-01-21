<?php

/**
 * This is used for eamil send.
 * @package    Email
 * @author     Icreon Tech  - dev2.
 */

namespace App\Repositories;

use Illuminate\Contracts\Mail\Mailer;
use App\Models\Emailtemplate;

/**
 * This is used for eamil send.
 * @package    Email
 * @author     Icreon Tech  - dev2.
 */
class EmailRepository extends BaseRepository {

    /**
     * The Mailer instance.
     */
    protected $mailer;

    /**
     * Create a new EmailRepository instance.
     * @param \Illuminate\Contracts\Mail\Mailer $mailer
     * @return void
     */
    public function __construct(Mailer $mailer) {
        $this->mailer = $mailer;
    }

    /**
     * This function send email to recipient
     * @author     Icreon Tech - dev2.
     * @param type array $emailParam
     * @param type int $templateId
     */
    public function sendEmail($emailParam, $templateId) { 
        $addressData = $this->getEmailData($emailParam['addressData']);
        $emailContent = $this->getEmailContent($emailParam['userData'], $templateId);
        $this->mailer->send('emails.default', ['body' => $emailContent['body']], function($message) use ($addressData, $emailContent) {
            if (isset($addressData['cc_email']) && $addressData['cc_email'] != '')
                $message->to($addressData['to_email'], $addressData['to_name'])->cc($addressData['cc_email'], $addressData['cc_name']);
            else
                $message->to($addressData['to_email'], $addressData['to_name']);

            $message->from($addressData['from_email'], $addressData['from_name']);
            $message->subject($emailContent['subject']);
        });
    }

    /**
     * This function return email content from given template
     * @author     Icreon Tech - dev2.
     * @param type array $templateTags
     * @param type int $templateId
     * @return type
     */
    public function getEmailContent($templateTags, $templateId) {
        $template = Emailtemplate::where('id', '=', $templateId)->first();
        $subject = $template->subject;
        $message = $template->message;
        $message = str_replace('email your query', '<a href="' . LIVE_WP_URL . '/send-enquiry">email your query</a>', $message);
        switch ($templateId):
            case 1:
                $message = str_replace('$first_name', $templateTags['first_name'], $message);
                $message = str_replace('$site_url', LIVE_WP_URL, $message);
                break;
            case 2:
                $message = str_replace('$username', $templateTags['username'], $message);
                $message = str_replace('$name', $templateTags['first_name'] . ' ' . $templateTags['last_name'], $message);
                $message = str_replace('$username', $templateTags['username'], $message);
                $message = str_replace('$password', $templateTags['password'], $message);
                $message = str_replace('email your query', '<a href="' . LIVE_WP_URL . '/send-enquiry">email your query</a>', $message);
                $message = str_replace('$site_url', LIVE_WP_URL, $message);
                break;
            case 4:
                if (session()->get('user')['user_type'] == SCHOOL) {
                    $message = str_replace('$name', $templateTags['school_name'], $message);
                } else {
                    $message = str_replace('$name', $templateTags['user_first_name'] . ' ' . $templateTags['user_last_name'], $message);
                }
                $message = str_replace('email your query', '<a href="' . LIVE_WP_URL . '/send-enquiry">email your query</a>', $message);
                $message = str_replace('$site_url', LIVE_WP_URL, $message);
                break;
            case 7:
                $message = str_replace('$name', $templateTags['first_name'] . ' ' . $templateTags['last_name'], $message);
                $message = str_replace('email your query', '<a href="' . LIVE_WP_URL . '/send-enquiry">email your query</a>', $message);
                $message = str_replace('$username', $templateTags['username'], $message);
                $message = str_replace('$password', $templateTags['password'], $message);
                $message = str_replace('$site_url', LIVE_WP_URL, $message);
                break;
            case 11:
                $message = str_replace('$username', $templateTags['username'], $message);
                $message = str_replace('$name', $templateTags['first_name'] . ' ' . $templateTags['last_name'], $message);
                $message = str_replace('$first_name', $templateTags['first_name'], $message);
                $message = str_replace('$last_name', $templateTags['last_name'], $message);
                $message = str_replace('email your query', '<a href="' . LIVE_WP_URL . '/send-enquiry">email your query</a>', $message);
                $message = str_replace('$site_url', LIVE_WP_URL, $message);
                break;
            case 14:
                $message = str_replace('$name', $templateTags['first_name'] . ' ' . $templateTags['last_name'], $message);
                $message = str_replace('$username', $templateTags['username'], $message);
                $message = str_replace('$school_name', $templateTags['school_name'], $message);
                $message = str_replace('email your query', '<a href="' . LIVE_WP_URL . '/send-enquiry">email your query</a>', $message);
                $message = str_replace('$site_url', LIVE_WP_URL, $message);
                break;
            case 15: //Parent structure
                $message = str_replace('$username', trim($templateTags['title'] . ' ' . $templateTags['first_name'] . ' ' . $templateTags['last_name']), $message);
                $message = str_replace('$first_name', $templateTags['first_name'], $message);
                $message = str_replace('$last_name', $templateTags['last_name'], $message);
                $message = str_replace('$title', $templateTags['title'], $message);
                $message = str_replace('$user_type', $templateTags['user_type'], $message);
                $message = str_replace('$email', $templateTags['email'], $message);
                $message = str_replace('$county', $templateTags['county'], $message);
                $message = str_replace('$city', $templateTags['city'], $message);
                $message = str_replace('$contact_no', isset($templateTags['contact_no']) ? ($templateTags['contact_no']) : '', $message);
                $message = str_replace('$how_hear', $templateTags['how_hear'], $message);
                $message = str_replace('$site_url', LIVE_WP_URL, $message);
                break;
            case 16:  //Teacher structure
                $message = str_replace('$username', trim($templateTags['title'] . ' ' . $templateTags['first_name'] . ' ' . $templateTags['last_name']), $message);
                $message = str_replace('$first_name', $templateTags['first_name'], $message);
                $message = str_replace('$last_name', $templateTags['last_name'], $message);
                $message = str_replace('$title', $templateTags['title'], $message);
                $message = str_replace('$user_type', $templateTags['user_type'], $message);
                $message = str_replace('$email', $templateTags['email'], $message);
                $message = str_replace('$school', $templateTags['school'], $message);
                $message = str_replace('$city', $templateTags['city'], $message);
                $message = str_replace('$job_role', $templateTags['job_role'], $message);
                $message = str_replace('$postal_code', $templateTags['postal_code'], $message);
                $message = str_replace('$contact_no', isset($templateTags['contact_no']) ? ($templateTags['contact_no']) : '', $message);
                $message = str_replace('$how_hear', $templateTags['how_hear'], $message);
                $message = str_replace('$site_url', LIVE_WP_URL, $message);
                break;
            case 17: //Tutor structure
                $message = str_replace('$username', trim($templateTags['first_name'] . ' ' . $templateTags['last_name']), $message);
                $message = str_replace('$first_name', $templateTags['first_name'], $message);
                $message = str_replace('$last_name', $templateTags['last_name'], $message);
                $message = str_replace('$title', $templateTags['title'], $message);
                $message = str_replace('$user_type', $templateTags['user_type'], $message);
                $message = str_replace('$email', $templateTags['email'], $message);
                $message = str_replace('$contact_no', isset($templateTags['contact_no']) ? ($templateTags['contact_no']) : '', $message);
                $message = str_replace('$how_hear', $templateTags['how_hear'], $message);
                $message = str_replace('$site_url', LIVE_WP_URL, $message);
                break;
            case 18: //demo request email
                $message = str_replace('$first_name', trim($templateTags['title'] . ' ' . $templateTags['first_name']), $message);
                $message = str_replace('$username', $templateTags['username'], $message);
                $message = str_replace('$child_username', $templateTags['child_username'], $message);
                $message = str_replace('$password', $templateTags['password'], $message);
                break;
            case 20: //forgot username
                $message = str_replace('$username', trim($templateTags['username']), $message);
                $message = str_replace('$name', trim($templateTags['name']), $message);
                $message = str_replace('contact us', '<a href="' . LIVE_WP_URL . '/send-enquiry">Contact Us</a>', $message);
                $message = str_replace('$nforgotusernameame', trim($templateTags['name']), $message);
                $message = str_replace('email your query', '<a href="' . LIVE_WP_URL . '/send-enquiry">email your query</a>', $message);
                $message = str_replace('$site_url', LIVE_WP_URL, $message);
                break;
            case 21: //Added new teacher
                $message = str_replace('$username', trim($templateTags['username']), $message);
                $message = str_replace('email your query', '<a href="' . LIVE_WP_URL . '/send-enquiry">email your query</a>', $message);
                $message = str_replace('$site_url', LIVE_WP_URL, $message);
                break;
            case 22: //Upgrade an Account Request
                $message = str_replace('{Name}', trim($templateTags['username']), $message);
                $message = str_replace('email your query', '<a href="' . LIVE_WP_URL . '/send-enquiry">email your query</a>', $message);
                $message = str_replace('$site_url', LIVE_WP_URL, $message);
                break;
            case 23: //Upgrade an Account Success
                $message = str_replace('{Name}', trim($templateTags['username']), $message);
                $message = str_replace('email your query', '<a href="' . LIVE_WP_URL . '/send-enquiry">email your query</a>', $message);
                $message = str_replace('$site_url', LIVE_WP_URL, $message);
                break;
            case 38: //Payment Email with Invoice
                if(strtolower($templateTags['payment_type']) == 'invoiced')
                    $templateTags['payment_type'] = 'Invoice';

                $message = str_replace('$heading', trim(strtoupper($templateTags['payment_type'])), $message);
                $message = str_replace('$address', trim($templateTags['address']), $message);
                $message = str_replace('$city', trim($templateTags['city']), $message);
                $message = str_replace('$transaction_id', trim($templateTags['transaction_id']), $message);
                $message = str_replace('$email', trim($templateTags['email']), $message);
                $message = str_replace('$school_name', trim($templateTags['school_name']), $message);
                $message = str_replace('$transaction_id', trim($templateTags['transaction_id']), $message);
                $message = str_replace('$discount', CURRENCY . trim($templateTags['discount']), $message);
                $message = str_replace('$payment_date', ($templateTags['payment_date']), $message);
                $message = str_replace('$plan_students', trim($templateTags['plan_students']), $message);
                $message = str_replace('$additional_students', trim($templateTags['additional_students']), $message);
                $message = str_replace('$plan_amount', CURRENCY . trim($templateTags['plan_amount']), $message);
                $message = str_replace('$additional_amount', CURRENCY . trim($templateTags['additional_amount']), $message);
                $message = str_replace('$discount', CURRENCY . trim($templateTags['discount']), $message);
                $message = str_replace('$total', CURRENCY . trim($templateTags['total']), $message);
                $message = str_replace('email your query', '<a href="' . LIVE_WP_URL . '/send-enquiry">email your query</a>', $message);
                $message = str_replace('$site_url', LIVE_WP_URL, $message);
                break;
            case 42: //Payment Email with Invoice
                if(strtolower($templateTags['payment_type']) == 'invoiced')
                    $templateTags['payment_type'] = 'Invoice';
                $message = str_replace('$heading', trim(strtoupper($templateTags['payment_type'])), $message);
                $message = str_replace('$address', trim($templateTags['address']), $message);
                $message = str_replace('$city', trim($templateTags['city']), $message);
                $message = str_replace('$transaction_id', trim($templateTags['transaction_id']), $message);
                $message = str_replace('$email', trim($templateTags['email']), $message);
                $message = str_replace('$school_name', trim($templateTags['school_name']), $message);
                $message = str_replace('$transaction_id', trim($templateTags['transaction_id']), $message);
                $message = str_replace('$discount', CURRENCY . trim($templateTags['discount']), $message);
                $message = str_replace('$payment_date', ($templateTags['payment_date']), $message);
                $message = str_replace('$plan_students', trim($templateTags['plan_students']), $message);
                $message = str_replace('$additional_students', trim($templateTags['additional_students']), $message);
                $message = str_replace('$plan_amount', CURRENCY . trim($templateTags['total']), $message);
                $message = str_replace('$additional_amount', CURRENCY . trim($templateTags['additional_amount']), $message);
                $message = str_replace('$discount', CURRENCY . trim($templateTags['discount']), $message);
                $message = str_replace('$total', CURRENCY . trim($templateTags['total']), $message);
                $message = str_replace('email your query', '<a href="' . LIVE_WP_URL . '/send-enquiry">email your query</a>', $message);
                $message = str_replace('$site_url', LIVE_WP_URL, $message);
                break;
            case 41: //Invoice Payment Reminder
                $message = str_replace('$username', trim($templateTags['username']), $message);
                $message = str_replace('$name', trim($templateTags['name']), $message);
                $message = str_replace('email your query', '<a href="' . LIVE_WP_URL . '/send-enquiry">email your query</a>', $message);
                $message = str_replace('$site_url', LIVE_WP_URL, $message);
                break;
            case 24: //Topic Results Parent
                $message = str_replace('$name', trim($templateTags['name']), $message);
                $message = str_replace('$pupil_name', trim($templateTags['pupil_name']), $message);
                $message = str_replace('$test_name', trim($templateTags['test_name']), $message);
                $message = str_replace('$percentage', trim($templateTags['percentage']), $message);
                $message = str_replace('$enquery_url', LIVE_WP_URL . '/send-enquiry', $message);
                $message = str_replace('$site_url', LIVE_WP_URL, $message);
                break;
            case 25: //Topic Results Parent
                $message = str_replace('$name', trim($templateTags['name']), $message);
                $message = str_replace('$pupil_name', trim($templateTags['pupil_name']), $message);
                $message = str_replace('$topic_name', trim($templateTags['topic_name']), $message);
                $message = str_replace('$percentage', trim($templateTags['percentage']), $message);
                $message = str_replace('$enquery_url', LIVE_WP_URL . '/send-enquiry', $message);
                $message = str_replace('$site_url', LIVE_WP_URL, $message);
                break;
            case 29: //Email Notice Assigned Topic Notice
                $message = str_replace('$name', trim($templateTags['name']), $message);
                $message = str_replace('$topic_name', trim($templateTags['topic_name']), $message);
                $message = str_replace('$teacher_name', trim($templateTags['teacher_name']), $message);
                $message = str_replace('$enquery_url', LIVE_WP_URL . '/send-enquiry', $message);
                $message = str_replace('$site_url', LIVE_WP_URL, $message);
                break;
            case 30: //Email Notice Assigned Test Notice
                $message = str_replace('$name', trim($templateTags['name']), $message);
                $message = str_replace('$test_name', trim($templateTags['test_name']), $message);
                $message = str_replace('$teacher_name', trim($templateTags['teacher_name']), $message);
                $message = str_replace('$enquery_url', LIVE_WP_URL . '/send-enquiry', $message);
                $message = str_replace('$site_url', LIVE_WP_URL, $message);
                break;
            case 43: //demo request email
                $message = str_replace('$first_name', trim($templateTags['title'] . ' ' . $templateTags['first_name']), $message);
                $message = str_replace('$username', $templateTags['username'], $message);
                $message = str_replace('$child_username', $templateTags['child_username'], $message);
                $message = str_replace('$password', $templateTags['password'], $message);
                break;            
        endswitch;
        return array('subject' => $subject, 'body' => $message);
    }

    /**
     * This function return email data (to and from) 
     * @author     Icreon Tech - dev2.
     * @param type array $addressData
     * @return type
     */
    public function getEmailData($addressData) {
        $data['to_email'] = $addressData['to_email'];
        $data['to_name'] = isset($addressData['to_name']) ? $addressData['to_name'] : '';
        $data['cc_email'] = isset($addressData['cc_email']) ? $addressData['cc_email'] : '';
        $data['cc_name'] = isset($addressData['cc_name']) ? $addressData['cc_name'] : '';
        $data['from_email'] = isset($addressData['from_email']) ? $addressData['from_email'] : env('ADMIN_FROM_EMAIL');
        $data['from_name'] = isset($addressData['from_name']) ? $addressData['from_name'] : env('ADMIN_FROM_NAME');
        return $data;
    }

}
