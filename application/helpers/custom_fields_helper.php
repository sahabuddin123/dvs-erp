<?php
defined('BASEPATH') or exit('No direct script access allowed');

function render_custom_Fields($belongs_to, $branch_id = null, $edit_id = false, $col_sm = null)
{
    $CI = &get_instance();
    if (empty($branch_id)) {
    	$branch_id = $CI->application_model->get_branch_id();
    }
    $CI->db->from('custom_field');
    $CI->db->where('status', 1);
    $CI->db->where('form_to', $belongs_to);
    $CI->db->where('branch_id', $branch_id);
    $CI->db->order_by('field_order','asc');
    $fields = $CI->db->get()->result_array();
    if (count($fields)) {
    	$html = '';
    	foreach ($fields as $field_key => $field) {
            $fieldLabel = ucfirst($field['field_label']);
            $fieldType = $field['field_type'];
            $bsColumn = $field['bs_column'];
            $required = $field['required'];
            $formTo = $field['form_to'];
            $fieldID = $field['id'];

            if ($bsColumn == '' || $bsColumn == 0) {
            	$bsColumn = 12;
            }
            $value = $field['default_value'];

            if ($edit_id !== false) {
                $return = get_custom_field_value($edit_id, $fieldID, $formTo);
                if ($return !== null && $return !== '') {
                    $value = $return;
                }
            }

	        if(isset($_POST['custom_fields'][$formTo][$fieldID])) {
	        	$value = $_POST['custom_fields'][$formTo][$fieldID];
	        }

	      	if ($fieldType != 'checkbox') {  
            	$html .= '<div class="col-md-' . $bsColumn . ' mb-sm"><div class="form-group">';
            	$html .= '<label class="control-label">' . $fieldLabel . ($required == 1 ? ' <span class="required">*</span>' : '') . '</label>';
	            if ($fieldType == 'text' || $fieldType == 'number' || $fieldType == 'email') {
	            	$html .= '<input type="' . $fieldType . '" class="form-control" autocomplete="off" name="custom_fields[' . $formTo . '][' . $fieldID . ']" value="' . $value . '" />';
	            }
	            if ($fieldType == 'textarea') {
	            	$html .= '<textarea type="' . $fieldType . '" class="form-control" name="custom_fields[' . $formTo . '][' . $fieldID . ']">' . $value . '</textarea>';
	            }
	            if ($fieldType == 'dropdown') {
	            	$html .= '<select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="custom_fields[' . $formTo . '][' . $fieldID . ']">';
	            	$html .= dropdownField($field['default_value'], $value);
	            	$html .= '</select>';
	            }
	            if ($fieldType == 'date') {
	            	$html .= '<input type="text" class="form-control" data-plugin-datepicker autocomplete="off" name="custom_fields[' . $formTo . '][' . $fieldID . ']" value="' . $value . '" />';
	            }
	            $html .= '<span class="error">' . form_error('custom_fields[' . $formTo . '][' . $fieldID . ']') . '</span>';
	            $html .= '</div></div>';
	        } else {
            	$html .= '<div class="col-md-' . $bsColumn . ' mb-sm"><div class="checkbox-replace">';
            	$html .= '<label class="i-checks">';
            	$html .= '<input type="checkbox" name="custom_fields[' . $formTo . '][' . $fieldID . ']" value="1" ' . ($value == 1 ? 'checked' : '') . ' ><i></i>';
            	$html .= $fieldLabel;
            	$html .= '</label>';
            	$html .= '</div></div>';
	        }
    	}
    	return $html;
    } 
}

function dropdownField($default, $value)
{
	$options = explode(',', $default);
	$is_en = (function_exists('get_current_lang') && get_current_lang() === 'en');
	$input = '<option value="">' . translate('select') . '</option>';
	foreach ($options as $option_key => $option_value) {
		$trim_val = trim($option_value);
		$disp_val = $trim_val;
		if ($is_en) {
			if ($trim_val === 'হ্যা' || $trim_val === 'হ্যাঁ') $disp_val = 'Yes';
			elseif ($trim_val === 'না') $disp_val = 'No';
		} else {
			if (strtolower($trim_val) === 'yes') $disp_val = 'হ্যাঁ';
			elseif (strtolower($trim_val) === 'no') $disp_val = 'না';
		}
		$input .= '<option value="' . slugify($trim_val) . '" '. (slugify($trim_val) == $value ? 'selected' : '') .'>' . $disp_val . '</option>';
	}
	return $input;
}

if (!function_exists('get_custom_field_label_by_lang')) {
    function get_custom_field_label_by_lang($label)
    {
        $is_en = (function_exists('get_current_lang') && get_current_lang() === 'en');
        $clean_label = trim((string)$label);
        
        $map = array(
            'ছাত্র-ছাত্রীর পূর্ণ নাম (ইংরেজিতে) / Full Name' => array('en' => 'Student Full Name in English', 'bn' => 'শিক্ষার্থীর পূর্ণ নাম ইংরেজিতে'),
            'ছাত্র-ছাত্রীর পূর্ণ নাম (ইংরেজিতে) / Full Name *' => array('en' => 'Student Full Name in English', 'bn' => 'শিক্ষার্থীর পূর্ণ নাম ইংরেজিতে'),
            'ছাত্র-ছাত্রীর পূর্ণ নাম (ইংরেজিতে)' => array('en' => 'Student Full Name in English', 'bn' => 'শিক্ষার্থীর পূর্ণ নাম ইংরেজিতে'),
            'ছাত্র-ছাত্রীর পূর্ণ নাম' => array('en' => 'Student Full Name', 'bn' => 'শিক্ষার্থীর পূর্ণ নাম'),
            'পিতার নাম (ইংরেজিতে)' => array('en' => 'Father Name in English', 'bn' => 'পিতার নাম ইংরেজিতে'),
            'পিতার নাম (বাংলায়)' => array('en' => 'Father Name in Bengali', 'bn' => 'পিতার নাম বাংলায়'),
            'পিতার নাম' => array('en' => 'Father Name', 'bn' => 'পিতার নাম'),
            'মাতার নাম (ইংরেজিতে)' => array('en' => 'Mother Name in English', 'bn' => 'মাতার নাম ইংরেজিতে'),
            'মাতার নাম (বাংলায়)' => array('en' => 'Mother Name in Bengali', 'bn' => 'মাতার নাম বাংলায়'),
            'মাতার নাম' => array('en' => 'Mother Name', 'bn' => 'মাতার নাম'),
            'জন্ম নিবন্ধন নং (Birth Reg No)' => array('en' => 'Birth Registration No', 'bn' => 'জন্ম নিবন্ধন নম্বর'),
            'জন্ম নিবন্ধন নং' => array('en' => 'Birth Registration No', 'bn' => 'জন্ম নিবন্ধন নম্বর'),
            'জন্ম নিবন্ধন নম্বর' => array('en' => 'Birth Registration No', 'bn' => 'জন্ম নিবন্ধন নম্বর'),
            'ইস্যুর তারিখ' => array('en' => 'Registration Issue Date', 'bn' => 'নিবন্ধন ইস্যুর তারিখ'),
            'নিবন্ধন ইস্যুর তারিখ (Issue Date)' => array('en' => 'Registration Issue Date', 'bn' => 'নিবন্ধন ইস্যুর তারিখ'),
            'নিবন্ধন ইস্যুর তারিখ' => array('en' => 'Registration Issue Date', 'bn' => 'নিবন্ধন ইস্যুর তারিখ'),
            'উপবৃত্তি প্রাপ্ত কিনা? (Stipend Eligible?)' => array('en' => 'Stipend Eligible', 'bn' => 'উপবৃত্তি সুবিধা'),
            'উপবৃত্তি প্রাপ্ত কিনা?' => array('en' => 'Stipend Eligible', 'bn' => 'উপবৃত্তি সুবিধা'),
            'উপবৃত্তি প্রাপ্ত কিনা' => array('en' => 'Stipend Eligible', 'bn' => 'উপবৃত্তি সুবিধা'),
            'শিক্ষার্থীর বয়স (বাংলায়)' => array('en' => 'Student Age', 'bn' => 'শিক্ষার্থীর বর্তমান বয়স'),
            'শিক্ষার্থীর বয়স' => array('en' => 'Student Age', 'bn' => 'শিক্ষার্থীর বর্তমান বয়স'),
            'অভিভাবকের বাৎসরিক আয় (বাংলায়)' => array('en' => 'Guardian Annual Income', 'bn' => 'অভিভাবকের বাৎসরিক আয়'),
            'অভিভাবকের বাৎসরিক আয়' => array('en' => 'Guardian Annual Income', 'bn' => 'অভিভাবকের বাৎসরিক আয়'),
            'পূর্ণ নাম (বাংলায়)' => array('en' => 'Full Name', 'bn' => 'পূর্ণ নাম'),
            'পূর্ণ নাম' => array('en' => 'Full Name', 'bn' => 'পূর্ণ নাম'),
            'এনআইডি/জন্ম নিবন্ধন নং' => array('en' => 'NID or Birth Registration No', 'bn' => 'এনআইডি বা জন্ম নিবন্ধন নম্বর'),
            'চাকুরীতে প্রথম যোগদানের তারিখ' => array('en' => 'First Joining Date', 'bn' => 'চাকুরীতে প্রথম যোগদানের তারিখ'),
            'প্রথম এমপিওভূক্তির তারিখ' => array('en' => 'First MPO Date', 'bn' => 'প্রথম এমপিওভুক্তির তারিখ'),
            'বর্তমান পদে এমপিওভূক্তির তারিখ' => array('en' => 'Current Post MPO Date', 'bn' => 'বর্তমান পদে এমপিওভুক্তির তারিখ'),
            'এমপিও কোড' => array('en' => 'MPO Code', 'bn' => 'এমপিও কোড'),
            'বেতন স্কেল' => array('en' => 'Pay Scale', 'bn' => 'বেতন স্কেল')
        );

        if (isset($map[$clean_label])) {
            return $is_en ? $map[$clean_label]['en'] : $map[$clean_label]['bn'];
        }

        if (function_exists('dvs_clean_bilingual')) {
            return dvs_clean_bilingual($clean_label, $is_en ? 'english' : 'bengali');
        }

        return $clean_label;
    }
}

function getCustomFields($belong_to, $branchID = '')
{
	$CI = &get_instance();
    if (empty($branchID)) {
        $branchID = $CI->application_model->get_branch_id();
    }
    $CI->db->from('custom_field');
    $CI->db->where('status', 1);
    $CI->db->where('form_to', $belong_to);
    $CI->db->where('branch_id', $branchID);
    $CI->db->order_by('field_order','asc');
    $fields = $CI->db->get()->result_array();
    return $fields;
}

function get_calculated_age_bangla($birthday, $target_date = null)
{
    if (empty($birthday) || $birthday == '0000-00-00') {
        return '';
    }
    try {
        $dob = new DateTime($birthday);
        $target = !empty($target_date) ? new DateTime($target_date) : new DateTime();
        $diff = $target->diff($dob);
        
        $years = $diff->y;
        $months = $diff->m;
        $days = $diff->d;

        $en_digits = array('0','1','2','3','4','5','6','7','8','9');
        $bn_digits = array('০','১','২','৩','৪','৫','৬','৭','৮','৯');

        $parts = array();
        if ($years > 0) {
            $parts[] = str_replace($en_digits, $bn_digits, $years) . ' বছর';
        }
        if ($months > 0) {
            $parts[] = str_replace($en_digits, $bn_digits, $months) . ' মাস';
        }
        if (empty($parts) && $days > 0) {
            $parts[] = str_replace($en_digits, $bn_digits, $days) . ' দিন';
        }
        return !empty($parts) ? implode(' ', $parts) : '০ বছর';
    } catch (Exception $e) {
        return '';
    }
}

function saveCustomFields($post, $userID)
{
	$CI = &get_instance();
	if (!is_array($post)) {
		$post = array();
	}

	// Auto calculate age in Bengali for custom field 'শিক্ষার্থীর বয়স (বাংলায়)' if not provided
	$CI->db->select('id');
	$CI->db->where('form_to', 'student');
	$CI->db->like('field_label', 'বয়স');
	$ageField = $CI->db->get('custom_field')->row_array();
	if (!empty($ageField)) {
		$ageFieldId = $ageField['id'];
		if (empty($post[$ageFieldId])) {
			$birthday = $CI->input->post('birthday');
			if (empty($birthday)) {
				$CI->db->select('birthday');
				$CI->db->where('id', $userID);
				$st = $CI->db->get('student')->row_array();
				if (!empty($st['birthday'])) {
					$birthday = $st['birthday'];
				}
			}
			if (!empty($birthday)) {
				$calculatedAge = get_calculated_age_bangla($birthday);
				if (!empty($calculatedAge)) {
					$post[$ageFieldId] = $calculatedAge;
				}
			}
		}
	}

	$arrayData = array();
	foreach ($post as $key => $value) {
		$insertData = array(
			'field_id' => $key, 
			'relid' => $userID, 
			'value' => (string)$value, 
		);
        $CI->db->where('relid', $userID);
        $CI->db->where('field_id', $key);
        $query = $CI->db->get('custom_fields_values');
        if ($query->num_rows() > 0) {
            $results = $query->row();
            $CI->db->where('id', $results->id);
            $CI->db->update('custom_fields_values', $insertData);
        } else {
            $CI->db->insert('custom_fields_values', $insertData);
        }
	}
}

function get_custom_field_value($rel_id, $field_id, $belongs_to)
{
	$CI = &get_instance();
    $CI->db->select('custom_fields_values.value');
    $CI->db->from('custom_field');
    $CI->db->join('custom_fields_values', 'custom_fields_values.field_id = custom_field.id and custom_fields_values.relid = ' . $rel_id, 'inner');
    $CI->db->where('custom_field.form_to', $belongs_to);
    $CI->db->where('custom_fields_values.field_id', $field_id);
    $row = $CI->db->get()->row_array();
    if (empty($row)) {
        return NULL;
    } else {
        return $row['value'];
    }
}

function custom_form_table($belong_to, $branch_id, $page = false)
{
    $CI = &get_instance();
    $CI->db->from('custom_field');
    $CI->db->where('status', 1);
    $CI->db->where('form_to', $belong_to);
    if ($page == false) {
        $CI->db->where('show_on_table', 1);
    }
    $CI->db->where('branch_id', $branch_id);
    $CI->db->order_by('field_order','asc');
    $fields = $CI->db->get()->result_array();
    return $fields;
}

function get_table_custom_field_value($field_id, $rel_id)
{
    $CI = &get_instance();
    $CI->db->from('custom_fields_values');
    $CI->db->where('relid', $rel_id);
    $CI->db->where('field_id', $field_id);
    $row = $CI->db->get()->row_array();
    if (empty($row)) {
        return NULL;
    } else {
        return $row['value'];
    }
}

// onlinea dmission custom_fields
function render_online_custom_fields($belongs_to, $branch_id = null, $edit_id = false, $col_sm = null)
{
    $CI = &get_instance();
    if (empty($branch_id)) {
        $branch_id = $CI->application_model->get_branch_id();
    }
    if ($edit_id == false) {
            $CI->db->select('custom_field.*,if(oaf.status is null, custom_field.status, oaf.status) as fstatus,if(oaf.required is null, custom_field.required, oaf.required) as required');
            $CI->db->from('custom_field');
            $CI->db->join('online_admission_fields as oaf', 'oaf.fields_id = custom_field.id and oaf.system = 0 and oaf.branch_id = ' . $branch_id, 'left');
            $CI->db->where('custom_field.status', 1);
            $CI->db->where('custom_field.form_to', $belongs_to);
            $CI->db->where('custom_field.branch_id', $branch_id);
            $CI->db->order_by('custom_field.field_order','asc');
            $fields = $CI->db->get()->result_array();
    } else {
        $CI->db->select('*,status as fstatus');
        $CI->db->from('custom_field');
        $CI->db->where('form_to', $belongs_to);
        $CI->db->where('branch_id', $branch_id);
        $CI->db->order_by('field_order','asc');
        $fields = $CI->db->get()->result_array();
    }

    if (count($fields)) {
        $html = '';
        foreach ($fields as $field_key => $field) {
            if ($field['fstatus'] == 1) {
                $fieldLabel = ucfirst($field['field_label']);
                $fieldType = $field['field_type'];
                $bsColumn = $field['bs_column'];
                $required = $field['required'];
                $formTo = $field['form_to'];
                $fieldID = $field['id'];

                if ($bsColumn == '' || $bsColumn == 0) {
                    $bsColumn == 12;
                }
                $value = $field['default_value'];

                if ($edit_id !== false) {
                    $return = get_online_custom_field_value($edit_id, $fieldID, $formTo);
                    if ($return !== null && $return !== '') {
                        $value = $return;
                    }
                }

                if(isset($_POST['custom_fields'][$formTo][$fieldID])) {
                    $value = $_POST['custom_fields'][$formTo][$fieldID];
                }

                if ($fieldType != 'checkbox') {  
                    $html .= '<div class="col-md-' . $bsColumn . ' mb-sm"><div class="form-group">';
                    $html .= '<label class="control-label">' . $fieldLabel . ($required == 1 ? ' <span class="required">*</span>' : '') . '</label>';
                    if ($fieldType == 'text' || $fieldType == 'number' || $fieldType == 'email') {
                        $html .= '<input type="' . $fieldType . '" class="form-control" autocomplete="off" name="custom_fields[' . $formTo . '][' . $fieldID . ']" value="' . $value . '" />';
                    }
                    if ($fieldType == 'textarea') {
                        $html .= '<textarea type="' . $fieldType . '" class="form-control" name="custom_fields[' . $formTo . '][' . $fieldID . ']">' . $value . '</textarea>';
                    }
                    if ($fieldType == 'dropdown') {
                        $html .= '<select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="custom_fields[' . $formTo . '][' . $fieldID . ']">';
                        $html .= dropdownField($field['default_value'], $value);
                        $html .= '</select>';
                    }
                    if ($fieldType == 'date') {
                        $html .= '<input type="text" class="form-control" data-plugin-datepicker autocomplete="off" name="custom_fields[' . $formTo . '][' . $fieldID . ']" value="' . $value . '" />';
                    }
                    $html .= '<span class="error">' . form_error('custom_fields[' . $formTo . '][' . $fieldID . ']') . '</span>';
                    $html .= '</div></div>';
                } else {
                    $html .= '<div class="col-md-' . $bsColumn . ' mb-sm"><div class="checkbox-replace">';
                    $html .= '<label class="i-checks">';
                    $html .= '<input type="checkbox" name="custom_fields[' . $formTo . '][' . $fieldID . ']" value="1" ' . ($value == 1 ? 'checked' : '') . ' ><i></i>';
                    $html .= $fieldLabel;
                    $html .= '</label>';
                    $html .= '</div></div>';
                }
            }
        }
        return $html;
    } 
}

function saveCustomFieldsOnline($post, $userID)
{
    $CI = &get_instance();
    if (!is_array($post)) {
        $post = array();
    }

    // Auto calculate age in Bengali for custom field 'শিক্ষার্থীর বয়স (বাংলায়)' if not provided
    $CI->db->select('id');
    $CI->db->where('form_to', 'student');
    $CI->db->like('field_label', 'বয়স');
    $ageField = $CI->db->get('custom_field')->row_array();
    if (!empty($ageField)) {
        $ageFieldId = $ageField['id'];
        if (empty($post[$ageFieldId])) {
            $birthday = $CI->input->post('birthday');
            if (empty($birthday)) {
                $CI->db->select('birthday');
                $CI->db->where('id', $userID);
                $st = $CI->db->get('online_admission')->row_array();
                if (!empty($st['birthday'])) {
                    $birthday = $st['birthday'];
                }
            }
            if (!empty($birthday)) {
                $calculatedAge = get_calculated_age_bangla($birthday);
                if (!empty($calculatedAge)) {
                    $post[$ageFieldId] = $calculatedAge;
                }
            }
        }
    }

    $arrayData = array();
    foreach ($post as $key => $value) {
        $insertData = array(
            'field_id' => $key, 
            'relid' => $userID, 
            'value' => (string)$value, 
        );
        $CI->db->where('relid', $userID);
        $CI->db->where('field_id', $key);
        $query = $CI->db->get('custom_fields_online_values');
        if ($query->num_rows() > 0) {
            $results = $query->row();
            $CI->db->where('id', $results->id);
            $CI->db->update('custom_fields_online_values', $insertData);
        } else {
            $CI->db->insert('custom_fields_online_values', $insertData);
        }
    }
}

function get_online_custom_field_value($rel_id, $field_id, $belongs_to)
{
    $CI = &get_instance();
    $CI->db->select('custom_fields_online_values.value');
    $CI->db->from('custom_field');
    $CI->db->join('custom_fields_online_values', 'custom_fields_online_values.field_id = custom_field.id and custom_fields_online_values.relid = ' . $rel_id, 'inner');
    $CI->db->where('custom_field.form_to', $belongs_to);
    $CI->db->where('custom_fields_online_values.field_id', $field_id);
    $row = $CI->db->get()->row_array();
    if (empty($row)) {
        return NULL;
    } else {
        return $row['value'];
    }
}

function get_online_custom_table_custom_field_value($field_id, $rel_id)
{
    $CI = &get_instance();
    $CI->db->from('custom_fields_online_values');
    $CI->db->where('relid', $rel_id);
    $CI->db->where('field_id', $field_id);
    $row = $CI->db->get()->row_array();
    if (empty($row)) {
        return NULL;
    } else {
        return $row['value'];
    }
}

function getOnlineCustomFields($belong_to, $branchID = '')
{
    $CI = &get_instance();
    if (empty($branchID)) {
        $branchID = $CI->application_model->get_branch_id();
    }
    $CI->db->select('custom_field.*,if(oaf.status is null, custom_field.status, oaf.status) as fstatus,if(oaf.required is null, custom_field.required, oaf.required) as required');
    $CI->db->from('custom_field');
    $CI->db->join('online_admission_fields as oaf', 'oaf.fields_id = custom_field.id and oaf.system = 0 and oaf.branch_id = ' . $branchID, 'left');
    $CI->db->where('custom_field.status', 1);
    $CI->db->where('custom_field.form_to', $belong_to);
    $CI->db->where('custom_field.branch_id', $branchID);
    $CI->db->order_by('custom_field.field_order','asc');
    $fields = $CI->db->get()->result_array();
    return $fields;
}

function render_single_custom_field($field, $belongs_to = 'student', $edit_id = false, $override_col = null, $custom_label = null, $placeholder = null, $icon = null)
{
    if (empty($field)) {
        return '';
    }
    $rawLabel = !empty($custom_label) ? $custom_label : ucfirst($field['field_label']);
    $fieldLabel = function_exists('get_custom_field_label_by_lang') ? get_custom_field_label_by_lang($rawLabel) : $rawLabel;
    $fieldType = $field['field_type'];
    $bsColumn = !empty($override_col) ? $override_col : (!empty($field['bs_column']) ? $field['bs_column'] : 4);
    $required = $field['required'];
    $formTo = !empty($field['form_to']) ? $field['form_to'] : $belongs_to;
    $fieldID = $field['id'];
    $name = "custom_fields[" . $formTo . "][" . $fieldID . "]";

    $value = $field['default_value'];
    if ($edit_id !== false) {
        $return = get_custom_field_value($edit_id, $fieldID, $formTo);
        if (!empty($return)) {
            $value = $return;
        }
    }
    if (isset($_POST['custom_fields'][$formTo][$fieldID])) {
        $value = $_POST['custom_fields'][$formTo][$fieldID];
    }

    $ph = !empty($placeholder) ? ' placeholder="' . html_escape($placeholder) . '"' : '';
    $reqAttr = ($required == 1) ? ' required' : '';

    $html = '<div class="col-md-' . $bsColumn . ' mb-sm"><div class="form-group">';
    $html .= '<label class="control-label">' . $fieldLabel . ($required == 1 ? ' <span class="required">*</span>' : '') . '</label>';
    
    if (!empty($icon) && $fieldType != 'checkbox' && $fieldType != 'textarea') {
        $html .= '<div class="input-group"><span class="input-group-addon"><i class="' . $icon . '"></i></span>';
    }

    if ($fieldType == 'text' || $fieldType == 'number' || $fieldType == 'email') {
        $html .= '<input type="' . $fieldType . '" class="form-control" autocomplete="off" name="' . $name . '" value="' . html_escape($value) . '"' . $ph . $reqAttr . ' />';
    } elseif ($fieldType == 'textarea') {
        $html .= '<textarea class="form-control" rows="2" name="' . $name . '"' . $ph . $reqAttr . '>' . html_escape($value) . '</textarea>';
    } elseif ($fieldType == 'dropdown') {
        $html .= '<select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="' . $name . '"' . $reqAttr . '>';
        $html .= dropdownField($field['default_value'], $value);
        $html .= '</select>';
    } elseif ($fieldType == 'date') {
        $html .= '<input type="text" class="form-control" data-plugin-datepicker autocomplete="off" name="' . $name . '" value="' . html_escape($value) . '"' . $ph . $reqAttr . ' />';
    } elseif ($fieldType == 'checkbox') {
        $html .= '<div class="checkbox-replace"><label class="i-checks"><input type="checkbox" name="[' . $formTo . '][' . $fieldID . ']" value="1" ' . ($value == 1 ? 'checked' : '') . '><i></i> ' . $fieldLabel . '</label></div>';
    }

    if (!empty($icon) && $fieldType != 'checkbox' && $fieldType != 'textarea') {
        $html .= '</div>';
    }

    $html .= '<span class="error">' . form_error($name) . '</span>';
    $html .= '</div></div>';
    return $html;
}

function render_custom_Fields_excluding($belongs_to, $branch_id = null, $edit_id = false, $col_sm = null, $exclude_ids = array())
{
    $CI = &get_instance();
    if (empty($branch_id)) {
        $branch_id = $CI->application_model->get_branch_id();
    }
    $CI->db->from('custom_field');
    $CI->db->where('status', 1);
    $CI->db->where('form_to', $belongs_to);
    $CI->db->where('branch_id', $branch_id);
    if (!empty($exclude_ids)) {
        $CI->db->where_not_in('id', $exclude_ids);
    }
    $CI->db->order_by('field_order','asc');
    $fields = $CI->db->get()->result_array();
    if (count($fields)) {
        $html = '';
        foreach ($fields as $field_key => $field) {
            $html .= render_single_custom_field($field, $belongs_to, $edit_id, $col_sm);
        }
        return $html;
    }
    return '';
}


function render_single_online_custom_field($field, $belongs_to = 'student', $edit_id = false, $override_col = null, $custom_label = null, $placeholder = null, $icon = null)
{
    if (empty($field) || (isset($field['fstatus']) && $field['fstatus'] == 0)) {
        return '';
    }
    $rawLabel = !empty($custom_label) ? $custom_label : ucfirst($field['field_label']);
    $fieldLabel = function_exists('get_custom_field_label_by_lang') ? get_custom_field_label_by_lang($rawLabel) : $rawLabel;
    $fieldType = $field['field_type'];
    $bsColumn = !empty($override_col) ? $override_col : (!empty($field['bs_column']) ? $field['bs_column'] : 4);
    $required = $field['required'];
    $formTo = !empty($field['form_to']) ? $field['form_to'] : $belongs_to;
    $fieldID = $field['id'];
    $name = "custom_fields[" . $formTo . "][" . $fieldID . "]";

    $value = $field['default_value'];
    if ($edit_id !== false) {
        $return = get_online_custom_field_value($edit_id, $fieldID, $formTo);
        if (!empty($return)) {
            $value = $return;
        }
    }
    if (isset($_POST['custom_fields'][$formTo][$fieldID])) {
        $value = $_POST['custom_fields'][$formTo][$fieldID];
    }

    $ph = !empty($placeholder) ? ' placeholder="' . html_escape($placeholder) . '"' : '';
    $reqAttr = ($required == 1) ? ' required' : '';

    $html = '<div class="col-md-' . $bsColumn . ' mb-3"><div class="form-group">';
    $html .= '<label class="form-label control-label">' . $fieldLabel . ($required == 1 ? ' <span class="required text-danger">*</span>' : '') . '</label>';
    
    if (!empty($icon) && $fieldType != 'checkbox' && $fieldType != 'textarea') {
        $html .= '<div class="input-group"><span class="input-group-text"><i class="' . $icon . '"></i></span>';
    }

    if ($fieldType == 'text' || $fieldType == 'number' || $fieldType == 'email') {
        $html .= '<input type="' . $fieldType . '" class="form-control" autocomplete="off" name="' . $name . '" value="' . html_escape($value) . '"' . $ph . $reqAttr . ' />';
    } elseif ($fieldType == 'textarea') {
        $html .= '<textarea class="form-control" rows="2" name="' . $name . '"' . $ph . $reqAttr . '>' . html_escape($value) . '</textarea>';
    } elseif ($fieldType == 'dropdown') {
        $html .= '<select class="form-select form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="' . $name . '"' . $reqAttr . '>';
        $html .= dropdownField($field['default_value'], $value);
        $html .= '</select>';
    } elseif ($fieldType == 'date') {
        $html .= '<input type="text" class="form-control" data-plugin-datepicker autocomplete="off" name="' . $name . '" value="' . html_escape($value) . '"' . $ph . $reqAttr . ' />';
    } elseif ($fieldType == 'checkbox') {
        $html .= '<div class="form-check"><input class="form-check-input" type="checkbox" name="[' . $formTo . '][' . $fieldID . ']" value="1" ' . ($value == 1 ? 'checked' : '') . ' id="cf_' . $fieldID . '"><label class="form-check-label" for="cf_' . $fieldID . '">' . $fieldLabel . '</label></div>';
    }

    if (!empty($icon) && $fieldType != 'checkbox' && $fieldType != 'textarea') {
        $html .= '</div>';
    }

    $html .= '<span class="error text-danger">' . form_error($name) . '</span>';
    $html .= '</div></div>';
    return $html;
}

function render_online_custom_fields_excluding($belongs_to, $branch_id = null, $edit_id = false, $col_sm = null, $exclude_ids = array())
{
    $CI = &get_instance();
    if (empty($branch_id)) {
        $branch_id = $CI->application_model->get_branch_id();
    }
    $allFields = getOnlineCustomFields($belongs_to, $branch_id);
    if (count($allFields)) {
        $html = '';
        foreach ($allFields as $field) {
            if (!in_array($field['id'], $exclude_ids) && $field['fstatus'] == 1) {
                $html .= render_single_online_custom_field($field, $belongs_to, $edit_id, $col_sm);
            }
        }
        return $html;
    }
    return '';
}
