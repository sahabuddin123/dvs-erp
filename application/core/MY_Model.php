<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#[\AllowDynamicProperties]
class MY_Model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	public function hash($password) {
		return hash("sha512", $password . config_item("encryption_key"));
	}
	
	public function uploadImage($role, $fields = "user_photo", $old_field = "") {
		$return_photo = 'defualt.png';
		if (empty($old_field)) {
			$old_field = 'old_' . $fields;
		}
		$old_user_photo = $this->input->post($old_field);
		if (empty($old_user_photo)) {
			$old_user_photo = $this->input->post('old_user_photo');
		}
		if (isset($_FILES["$fields"]) && !empty($_FILES["$fields"]['name'])) {
			$config['upload_path'] = './uploads/images/' . $role . '/';
			$config['allowed_types'] = '*';
			$config['overwrite'] = FALSE;
			$config['encrypt_name'] = TRUE;
			$this->upload->initialize($config);
			if ($this->upload->do_upload("$fields")) {
	            // need to unlink previous photo
	            if (!empty($old_user_photo) && $old_user_photo != 'defualt.png') {
	            	$unlink_path = 'uploads/images/' . $role . '/';
	                if (file_exists($unlink_path . $old_user_photo)) {
	                    @unlink($unlink_path . $old_user_photo);
	                }
	            }
				$return_photo = $this->upload->data('file_name');
				$uploaded_file = './uploads/images/' . $role . '/' . $return_photo;
				if (file_exists($uploaded_file)) {
					$this->processProfilePhoto($uploaded_file);
				}
			}
		} else {
			if (!empty($old_user_photo)){
				$return_photo = $old_user_photo;
			}
		}
		return $return_photo;
	}

	public function processProfilePhoto($file_path)
	{
		if (!file_exists($file_path)) {
			return;
		}
		$img_info = @getimagesize($file_path);
		if (!$img_info) {
			return;
		}

		$global_config = $this->db->get_where('global_settings', array('id' => 1))->row_array();
		$target_width = !empty($global_config['profile_photo_width']) ? (int)$global_config['profile_photo_width'] : 300;
		$target_height = !empty($global_config['profile_photo_height']) ? (int)$global_config['profile_photo_height'] : 300;
		$do_crop = isset($global_config['profile_photo_crop']) ? (int)$global_config['profile_photo_crop'] : 1;

		$mime = $img_info['mime'];
		$src_w = $img_info[0];
		$src_h = $img_info[1];

		switch ($mime) {
			case 'image/jpeg':
			case 'image/jpg':
				$src_img = @imagecreatefromjpeg($file_path);
				break;
			case 'image/png':
				$src_img = @imagecreatefrompng($file_path);
				break;
			case 'image/webp':
				$src_img = function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($file_path) : null;
				break;
			default:
				$src_img = null;
				break;
		}

		if (!$src_img) {
			return;
		}

		if ($do_crop == 1) {
			$src_ratio = $src_w / $src_h;
			$target_ratio = $target_width / $target_height;

			if ($src_ratio > $target_ratio) {
				$crop_w = (int)round($src_h * $target_ratio);
				$crop_h = $src_h;
				$src_x = (int)round(($src_w - $crop_w) / 2);
				$src_y = 0;
			} else {
				$crop_w = $src_w;
				$crop_h = (int)round($src_w / $target_ratio);
				$src_x = 0;
				$src_y = (int)round(($src_h - $crop_h) / 2);
			}

			$dst_img = imagecreatetruecolor($target_width, $target_height);
			if ($mime == 'image/png' || $mime == 'image/webp') {
				imagealphablending($dst_img, false);
				imagesavealpha($dst_img, true);
				$transparent = imagecolorallocatealpha($dst_img, 255, 255, 255, 127);
				imagefilledrectangle($dst_img, 0, 0, $target_width, $target_height, $transparent);
			}

			imagecopyresampled($dst_img, $src_img, 0, 0, $src_x, $src_y, $target_width, $target_height, $crop_w, $crop_h);
		} else {
			$ratio = min($target_width / $src_w, $target_height / $src_h, 1);
			$new_w = (int)round($src_w * $ratio);
			$new_h = (int)round($src_h * $ratio);

			$dst_img = imagecreatetruecolor($new_w, $new_h);
			if ($mime == 'image/png' || $mime == 'image/webp') {
				imagealphablending($dst_img, false);
				imagesavealpha($dst_img, true);
				$transparent = imagecolorallocatealpha($dst_img, 255, 255, 255, 127);
				imagefilledrectangle($dst_img, 0, 0, $new_w, $new_h, $transparent);
			}

			imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_w, $new_h, $src_w, $src_h);
		}

		switch ($mime) {
			case 'image/jpeg':
			case 'image/jpg':
				imagejpeg($dst_img, $file_path, 88);
				break;
			case 'image/png':
				imagepng($dst_img, $file_path, 6);
				break;
			case 'image/webp':
				if (function_exists('imagewebp')) {
					imagewebp($dst_img, $file_path, 85);
				}
				break;
		}

		imagedestroy($src_img);
		imagedestroy($dst_img);
	}

    public function get($table, $where_array = NULL, $single = false, $branch = false, $columns = '*')
    {
        $this->db->select($columns);
        if (is_array($where_array)){
            $this->db->where($where_array);
        }
        if ($branch == true) {
	        if (!is_superadmin_loggedin()) {
	            $this->db->where("branch_id", get_loggedin_branch_id());
	        }
        }
        if ($single == true) {
            $method = 'row_array';
        } else {
            $method = 'result_array';
            $this->db->order_by('id', 'ASC');
        }
        $result = $this->db->get($table)->$method();

		if (empty($result) && $single == true) {
		    $config = array();
		    $r = $this->db->list_fields($table);
		    foreach ($r as $key => $value) {
		        $config[$value] = "";
		    }
		    return $config;
		}
		
		return $result;
    }

    public function getSingle($table, $id = NULL, $single = false)
    {
        if ($single == true) {
            $method = 'row';
        } else {
            $method = 'result';
        }
        $q = $this->db->query("SELECT * FROM " . $table . " WHERE id = " . $this->db->escape($id));
		return $q->$method();
    }

    public function fileupload($media_name, $upload_path = "", $old_file = '', $enc = true)
    {
        if (file_exists($_FILES[$media_name]['tmp_name']) && !$_FILES[$media_name]['error'] == UPLOAD_ERR_NO_FILE) {
            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = '*';
            if ($enc == true) {
                $config['encrypt_name'] = true;
            } else {
                $config['overwrite'] = FALSE;
            }
            $this->upload->initialize($config);
            if ($this->upload->do_upload($media_name)) {
                if (!empty($old_file)) {
                    $file_name = $config['upload_path'] . $old_file;
                    if (file_exists($file_name)) {
                        unlink($file_name);
                    }
                }
                return $this->upload->data('file_name');
            }
        } else {
			if (!empty($old_file)){
				return $old_file;
			} else {
				return "";
			}
        }
    } 
}
