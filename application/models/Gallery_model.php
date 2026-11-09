<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gallery_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // test save and update function
    public function save($data)
    {
        $file_type = (!empty($data['file_type']) && $data['file_type'] == 'video') ? 'video' : 'image';
        $video_url = !empty($data['video_url']) ? trim($data['video_url']) : null;
        $title_en = !empty($data['gallery_title_en']) ? trim($data['gallery_title_en']) : null;
        $description_en = !empty($data['description_en']) ? trim($data['description_en']) : null;

        $insertGallery = array(
            'branch_id'      => $this->application_model->get_branch_id(),
            'title'          => $data['gallery_title'],
            'title_en'       => $title_en,
            'description'    => $data['description'],
            'description_en' => $description_en,
            'date'           => !empty($data['date']) ? $data['date'] : date("Y-m-d"),
            'category_id'    => $data['category_id'],
            'file_type'      => $file_type,
            'video_url'      => $video_url,
            'added_by'       => get_loggedin_user_id(),
            'show_web'       => (isset($_POST['show_website']) ? 1 : 0),
            'thumb_image'    => $this->upload_image(),
        );

        if (isset($data['gallery_id']) && !empty($data['gallery_id'])) {
            $insertGallery['alias'] = $this->slug->create_uri($insertGallery, $data['gallery_id']);
            $this->db->where('id', $data['gallery_id']);
            $this->db->update('front_cms_gallery_content', $insertGallery);
        } else {
            $insertGallery['elements'] = json_encode([]);
            $insertGallery['created_at'] = date("Y-m-d");
            $insertGallery['alias'] = $this->slug->create_uri($insertGallery);
            $this->db->insert('front_cms_gallery_content', $insertGallery);
        }
    }

    // upload gallery image
    public function upload_image($name = 'gallery')
    {
        $prev_image = $this->input->post('old_photo');
        $image = isset($_FILES['thumb_image']['name']) ? $_FILES['thumb_image']['name'] : '';
        $return_image = '';
        if ($image != '') {
            $destination = './uploads/frontend/gallery/';
            if (!is_dir($destination)) {
                @mkdir($destination, 0777, true);
            }
            $extension = pathinfo($image, PATHINFO_EXTENSION);
            $image_path = $name . '-' . time() . '.' . $extension;
            move_uploaded_file($_FILES['thumb_image']['tmp_name'], $destination . $image_path);

            // need to unlink previous gallery image
            if ($prev_image != '') {
                if (file_exists($destination . $prev_image)) {
                    @unlink($destination . $prev_image);
                }
            }
            $return_image = $image_path;
        } else {
            $return_image = $prev_image;
        }
        return $return_image;
    }

    public function get_image_url($file_path = '')
    {
        if (!empty($file_path)) {
            if (file_exists(FCPATH . 'uploads/frontend/gallery/' . $file_path)) {
                return base_url('uploads/frontend/gallery/' . $file_path);
            }
            if (file_exists(FCPATH . 'assets/frontend/dvs/storage/albums/' . $file_path)) {
                return base_url('assets/frontend/dvs/storage/albums/' . $file_path);
            }
            if (file_exists(FCPATH . 'assets/frontend/dvs/storage/galleries/' . $file_path)) {
                return base_url('assets/frontend/dvs/storage/galleries/' . $file_path);
            }
            if (file_exists('assets/frontend/dvs/storage/albums/' . $file_path)) {
                return base_url('assets/frontend/dvs/storage/albums/' . $file_path);
            }
            if (file_exists('assets/frontend/dvs/storage/galleries/' . $file_path)) {
                return base_url('assets/frontend/dvs/storage/galleries/' . $file_path);
            }
            if (file_exists('uploads/frontend/gallery/' . $file_path)) {
                return base_url('uploads/frontend/gallery/' . $file_path);
            }
        }
        // Fallback default image
        if (file_exists(FCPATH . 'uploads/frontend/gallery/dOE0oYuYQRh3bDbaMw6J9JQyEMO884GAv5lQ5YGq.jpg')) {
            return base_url('uploads/frontend/gallery/dOE0oYuYQRh3bDbaMw6J9JQyEMO884GAv5lQ5YGq.jpg');
        }
        return base_url('uploads/app_image/default.png');
    }

    public function get_youtube_video_id($url)
    {
        if (empty($url)) return null;
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $url, $match)) {
            return $match[1];
        }
        return null;
    }

    public function get_video_thumbnail($video_url, $custom_thumb = '')
    {
        if (!empty($custom_thumb)) {
            $url = $this->get_image_url($custom_thumb);
            if (!empty($url) && strpos($url, 'default.png') === false) {
                return $url;
            }
        }
        $vid = $this->get_youtube_video_id($video_url);
        if (!empty($vid)) {
            return "https://img.youtube.com/vi/{$vid}/hqdefault.jpg";
        }
        return $this->get_image_url($custom_thumb);
    }
}
