<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Whatsapp Messaging Addon Controller
 * Send notifications and direct messages to students, parents, and staff via WhatsApp
 */
class Whatsapp_messaging extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('whatsapp_messaging_model');
        // Ensure module is enabled
        if (function_exists('moduleIsEnabled') && !moduleIsEnabled('whatsapp_messaging')) {
            access_denied();
        }
    }

    public function index()
    {
        redirect(base_url('whatsapp_messaging/send'));
    }

    /**
     * Send WhatsApp Message (Compose & Dispatch)
     */
    public function send()
    {
        $branchID = $this->application_model->get_branch_id();
        $settings = $this->whatsapp_messaging_model->get_settings($branchID);
        $school_name = get_global_setting('institute_name');
        if (empty($school_name)) {
            $school_name = 'Dream Valley School';
        }

        if ($_POST) {
            $recipient_type = $this->input->post('recipient_type'); // 'student', 'parent', 'staff', 'custom'
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $role_id = $this->input->post('role_id');
            $raw_message = $this->input->post('message');
            $custom_numbers = $this->input->post('custom_numbers');

            if (empty($raw_message)) {
                set_alert('error', 'অনুগ্রহ করে মেসেজের বিবরণ লিখুন (Please enter message text)');
                redirect(base_url('whatsapp_messaging/send'));
            }

            // Gather recipients
            $recipients = [];
            if ($recipient_type === 'custom') {
                $numbers = explode(',', (string)$custom_numbers);
                foreach ($numbers as $num) {
                    $num = trim($num);
                    if (!empty($num)) {
                        $recipients[] = [
                            'name'         => 'সম্মানিত গ্রাহক / User',
                            'student_name' => '',
                            'roll'         => '',
                            'class'        => '',
                            'section'      => '',
                            'mobile'       => $num,
                            'role'         => 'custom'
                        ];
                    }
                }
            } else {
                $recipients = $this->whatsapp_messaging_model->get_recipients($recipient_type, [
                    'branch_id'  => $branchID,
                    'class_id'   => $class_id,
                    'section_id' => $section_id,
                    'role_id'    => $role_id
                ]);
            }

            if (empty($recipients)) {
                set_alert('error', 'কোনো প্রাপক পাওয়া যায়নি (No recipients found for selected criteria)');
                redirect(base_url('whatsapp_messaging/send'));
            }

            // Prepare header and footer
            $header = !empty($settings->header_text) ? "*{$settings->header_text}*\n\n" : '';
            $footer = !empty($settings->footer_text) ? "\n\n_{$settings->footer_text}_" : '';

            $dispatch_list = [];
            $api_success = 0;
            $api_fail = 0;

            foreach ($recipients as $item) {
                if (empty($item['mobile'])) {
                    continue;
                }

                // Variable Replacements
                $msg = $raw_message;
                $msg = str_replace('{name}', $item['name'], $msg);
                $msg = str_replace('{student_name}', $item['student_name'], $msg);
                $msg = str_replace('{roll}', $item['roll'], $msg);
                $msg = str_replace('{class}', $item['class'], $msg);
                $msg = str_replace('{section}', $item['section'], $msg);
                $msg = str_replace('{school}', $school_name, $msg);
                $msg = str_replace('{date}', _d(date('Y-m-d')), $msg);

                $full_message = $header . $msg . $footer;
                $formatted_phone = $this->whatsapp_messaging_model->format_number($item['mobile'], $settings->country_code);
                $whatsapp_url = $this->whatsapp_messaging_model->generate_whatsapp_url($formatted_phone, $full_message);

                if ($settings->gateway_type === 'custom_api') {
                    $res = $this->whatsapp_messaging_model->dispatch_api($formatted_phone, $full_message, $settings);
                    $status = ($res['status'] === 'success') ? 'sent' : 'failed';
                    if ($status === 'sent') {
                        $api_success++;
                    } else {
                        $api_fail++;
                    }

                    // Save log
                    $this->whatsapp_messaging_model->save_log([
                        'branch_id'        => $branchID,
                        'recipient_name'   => $item['name'],
                        'recipient_mobile' => $formatted_phone,
                        'recipient_role'   => $item['role'],
                        'message'          => $full_message,
                        'gateway_type'     => 'custom_api',
                        'status'           => $status,
                        'response'         => substr((string)$res['response'], 0, 500),
                        'sent_by'          => get_loggedin_user_id(),
                        'created_at'       => date('Y-m-d H:i:s')
                    ]);
                } else {
                    // Web / Click-to-Chat Queue mode
                    $log_id = $this->whatsapp_messaging_model->save_log([
                        'branch_id'        => $branchID,
                        'recipient_name'   => $item['name'],
                        'recipient_mobile' => $formatted_phone,
                        'recipient_role'   => $item['role'],
                        'message'          => $full_message,
                        'gateway_type'     => 'web',
                        'status'           => 'ready_to_send',
                        'response'         => '',
                        'sent_by'          => get_loggedin_user_id(),
                        'created_at'       => date('Y-m-d H:i:s')
                    ]);

                    $dispatch_list[] = [
                        'log_id'          => $log_id,
                        'name'            => $item['name'],
                        'mobile'          => $item['mobile'],
                        'formatted_phone' => $formatted_phone,
                        'role'            => $item['role'],
                        'message'         => $full_message,
                        'whatsapp_url'    => $whatsapp_url
                    ];
                }
            }

            if ($settings->gateway_type === 'custom_api') {
                set_alert('success', "API এর মাধ্যমে মোট {$api_success} টি বার্তা পাঠানো হয়েছে! (ব্যর্থ: {$api_fail})");
                redirect(base_url('whatsapp_messaging/reports'));
            } else {
                // Pass dispatch list to Queue view
                $this->data['dispatch_list'] = $dispatch_list;
                $this->data['title'] = 'WhatsApp বার্তা প্রেরণ কিউ (Queue)';
                $this->data['sub_page'] = 'whatsapp_messaging/queue';
                $this->data['main_menu'] = 'whatsapp_messaging';
                $this->load->view('layout/index', $this->data);
                return;
            }
        }

        $this->data['settings'] = $settings;
        $this->data['templates'] = $this->whatsapp_messaging_model->get_templates($branchID);
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = 'WhatsApp বার্তা পাঠান (Send Message)';
        $this->data['sub_page'] = 'whatsapp_messaging/send';
        $this->data['main_menu'] = 'whatsapp_messaging';
        $this->load->view('layout/index', $this->data);
    }

    /**
     * Message Templates
     */
    public function templates()
    {
        $branchID = $this->application_model->get_branch_id();

        if ($_POST) {
            $id = $this->input->post('id');
            $title = trim($this->input->post('title'));
            $category = $this->input->post('category');
            $template_body = trim($this->input->post('template_body'));

            if (!empty($title) && !empty($template_body)) {
                $data = [
                    'branch_id'     => $branchID,
                    'title'         => $title,
                    'category'      => $category ?: 'general',
                    'template_body' => $template_body
                ];
                $this->whatsapp_messaging_model->save_template($data, $id);
                set_alert('success', translate('information_has_been_updated_successfully'));
            }
            redirect(base_url('whatsapp_messaging/templates'));
        }

        $this->data['templates'] = $this->whatsapp_messaging_model->get_templates($branchID);
        $this->data['title'] = 'WhatsApp মেসেজ টেমপ্লেট (Templates)';
        $this->data['sub_page'] = 'whatsapp_messaging/templates';
        $this->data['main_menu'] = 'whatsapp_messaging';
        $this->load->view('layout/index', $this->data);
    }

    /**
     * Delete Template
     */
    public function template_delete($id)
    {
        $this->whatsapp_messaging_model->delete_template($id);
        set_alert('success', translate('information_deleted'));
        redirect(base_url('whatsapp_messaging/templates'));
    }

    /**
     * Sent Message Reports & Logs
     */
    public function reports()
    {
        $filters = [
            'role'      => $this->input->get('role'),
            'date_from' => $this->input->get('date_from'),
            'date_to'   => $this->input->get('date_to')
        ];

        $this->data['logs'] = $this->whatsapp_messaging_model->get_logs($filters, 250);
        $this->data['filters'] = $filters;
        $this->data['title'] = 'WhatsApp মেসেজ লগ ও রিপোর্ট (Reports)';
        $this->data['sub_page'] = 'whatsapp_messaging/reports';
        $this->data['main_menu'] = 'whatsapp_messaging';
        $this->load->view('layout/index', $this->data);
    }

    /**
     * Settings & Gateway Configuration
     */
    public function settings()
    {
        if (!is_superadmin_loggedin() && !is_admin_loggedin()) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();

        if ($_POST) {
            $is_active = $this->input->post('is_active') ? 1 : 0;
            $gateway_type = $this->input->post('gateway_type') ?: 'web';
            $api_url = trim($this->input->post('api_url'));
            $api_key = trim($this->input->post('api_key'));
            $instance_id = trim($this->input->post('instance_id'));
            $country_code = trim($this->input->post('country_code')) ?: '880';
            $header_text = trim($this->input->post('header_text'));
            $footer_text = trim($this->input->post('footer_text'));

            $data = [
                'branch_id'    => $branchID,
                'is_active'    => $is_active,
                'gateway_type' => $gateway_type,
                'api_url'      => $api_url,
                'api_key'      => $api_key,
                'instance_id'  => $instance_id,
                'country_code' => $country_code,
                'header_text'  => $header_text,
                'footer_text'  => $footer_text,
                'updated_at'   => date('Y-m-d H:i:s')
            ];

            $this->whatsapp_messaging_model->save_settings($data);
            set_alert('success', translate('information_has_been_updated_successfully'));
            redirect(base_url('whatsapp_messaging/settings'));
        }

        $this->data['settings'] = $this->whatsapp_messaging_model->get_settings($branchID);
        $this->data['title'] = 'WhatsApp কনফিগারেশন ও গেটওয়ে সেটিংস';
        $this->data['sub_page'] = 'whatsapp_messaging/settings';
        $this->data['main_menu'] = 'whatsapp_messaging';
        $this->load->view('layout/index', $this->data);
    }

    /**
     * Update log status via AJAX when a WhatsApp link is clicked
     */
    public function update_log_status()
    {
        $log_id = (int)$this->input->post('log_id');
        $status = $this->input->post('status') ?: 'sent';
        if ($log_id > 0) {
            $this->db->where('id', $log_id)->update('whatsapp_messaging_logs', ['status' => $status]);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'fail']);
        }
        exit();
    }
}
