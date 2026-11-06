<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Thermal_print_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('fees_model');
    }

    /**
     * Get Thermal Print settings for a branch or globally
     */
    public function get_settings($branch_id = 0)
    {
        if ($this->db->table_exists('thermal_print_settings')) {
            if ($branch_id > 0) {
                $row = $this->db->where('branch_id', $branch_id)->get('thermal_print_settings')->row();
                if ($row) {
                    return $row;
                }
            }
            $row = $this->db->order_by('id', 'ASC')->get('thermal_print_settings')->row();
            if ($row) {
                return $row;
            }
        }

        $school_name = get_global_setting('institute_name');
        if (empty($school_name)) {
            $school_name = 'ড্রিম ভ্যালি স্কুল';
        }

        return (object)[
            'id'              => 1,
            'branch_id'       => 0,
            'is_active'       => 1,
            'printer_width'   => '80mm',
            'show_logo'       => 1,
            'show_barcode'    => 1,
            'show_qr'         => 1,
            'header_title'    => $school_name,
            'header_subtitle' => 'ইসলামিক কারিকুলাম উইথ তাহফিজুল কুরআন',
            'address'         => 'খাসেরহাট মেইন রোড, চরবাটা, সুবর্ণচর, নোয়াখালী',
            'contact_info'    => 'মোবাইল: 01716-122899',
            'footer_text'     => 'ধন্যবাদ! ফি একবার পরিশোধের পর তা ফেরতযোগ্য নয়।',
            'auto_print'      => 1
        ];
    }

    /**
     * Save settings
     */
    public function save_settings($data)
    {
        if (!$this->db->table_exists('thermal_print_settings')) {
            return false;
        }

        $branch_id = isset($data['branch_id']) ? (int)$data['branch_id'] : 0;
        $exist = $this->db->where('branch_id', $branch_id)->get('thermal_print_settings')->row();

        if ($exist) {
            $this->db->where('id', $exist->id)->update('thermal_print_settings', $data);
            return $exist->id;
        } else {
            $this->db->insert('thermal_print_settings', $data);
            return $this->db->insert_id();
        }
    }

    /**
     * Gather invoice data formatted for thermal receipt
     */
    public function get_invoice_thermal_data($studentID, $record_array)
    {
        $basic = $this->fees_model->getInvoiceBasic($studentID);
        $total_amount = 0;
        $total_discount = 0;
        $total_fine = 0;
        $total_paid = 0;
        $total_balance = 0;
        $items = [];

        foreach ($record_array as $val) {
            $deposit = [];
            $fee_title = '';
            if ($val->feeType == 'general') {
                $deposit = $this->fees_model->getStudentFeeDeposit($val->allocationID, $val->feeTypeID);
                $fee_title = get_type_name_by_id('fees_type', $val->feeTypeID);
            } elseif ($val->feeType == 'transport') {
                $deposit = $this->fees_model->getStudentTransportFeeDeposit($val->trans_fd_id);
                $fee_title = translate('transport_fees');
            }

            $full_amount = (float)$val->feeAmount;
            $type_discount = (float)$deposit['total_discount'];
            $type_fine = (float)$deposit['total_fine'];
            $type_amount = (float)$deposit['total_amount'];
            $balance = $full_amount - ($type_amount + $type_discount);

            $total_amount += $full_amount;
            $total_discount += $type_discount;
            $total_fine += $type_fine;
            $total_paid += $type_amount;
            $total_balance += $balance;

            $items[] = [
                'title'    => $fee_title,
                'amount'   => $full_amount,
                'discount' => $type_discount,
                'fine'     => $type_fine,
                'paid'     => $type_amount,
                'balance'  => $balance
            ];
        }

        return [
            'basic'          => $basic,
            'items'          => $items,
            'total_amount'   => $total_amount,
            'total_discount' => $total_discount,
            'total_fine'     => $total_fine,
            'total_paid'     => $total_paid,
            'total_balance'  => $total_balance
        ];
    }

    /**
     * Gather payment history/slip data formatted for thermal receipt
     */
    public function get_receipt_thermal_data($studentID, $record_array)
    {
        $basic = $this->fees_model->getInvoiceBasic($studentID);
        $total_paid = 0;
        $total_discount = 0;
        $total_fine = 0;
        $items = [];
        $payment_date = date('Y-m-d');
        $pay_via = 'Cash';
        $collect_by_name = '';

        foreach ($record_array as $row) {
            $payment_id = $row->payment_id;
            $history = $this->db->get_where('fee_payment_history', ['id' => $payment_id])->row();
            if ($history) {
                $payment_date = $history->date;
                $pay_via = $history->pay_via;
                $collect_by = $history->collect_by;
                if ($collect_by == 'online') {
                    $collect_by_name = translate('online');
                } else {
                    $collect_by_name = get_type_name_by_id('staff', $collect_by);
                }

                $total_paid += (float)$history->amount;
                $total_discount += (float)$history->discount;
                $total_fine += (float)$history->fine;

                $fee_name = '';
                if ($history->allocation_id > 0) {
                    $fee_name = get_type_name_by_id('fees_type', $history->type_id);
                } else {
                    $fee_name = translate('transport_fees');
                }

                $items[] = [
                    'id'       => $history->id,
                    'title'    => $fee_name,
                    'amount'   => (float)$history->amount,
                    'discount' => (float)$history->discount,
                    'fine'     => (float)$history->fine,
                    'remarks'  => $history->remarks
                ];
            }
        }

        return [
            'basic'           => $basic,
            'items'           => $items,
            'total_paid'      => $total_paid,
            'total_discount'  => $total_discount,
            'total_fine'      => $total_fine,
            'payment_date'    => $payment_date,
            'pay_via'         => $pay_via,
            'collect_by_name' => $collect_by_name
        ];
    }
}

