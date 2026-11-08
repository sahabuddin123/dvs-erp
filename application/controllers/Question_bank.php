<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Question_bank extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!moduleIsEnabled('question_bank')) {
            access_denied();
        }
        $this->load->model('question_bank_model');
        $this->load->model('subject_model');
    }

    /**
     * Question Repository List & Filter
     */
    public function index()
    {
        if (!get_permission('question_bank', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        $classID = $this->input->get('class_id');
        $subjectID = $this->input->get('subject_id');
        $chapterID = $this->input->get('chapter_id');
        $topicID = $this->input->get('topic_id');
        $type = $this->input->get('type');
        $difficulty = $this->input->get('difficulty');
        $taxonomy = $this->input->get('taxonomy');
        $status = $this->input->get('status');
        $search = $this->input->get('search');

        $filters = array(
            'branch_id' => $branchID,
            'class_id' => $classID,
            'subject_id' => $subjectID,
            'chapter_id' => $chapterID,
            'topic_id' => $topicID,
            'type' => $type,
            'difficulty' => $difficulty,
            'taxonomy' => $taxonomy,
            'status' => $status,
            'search' => $search
        );

        $this->data['branch_id'] = $branchID;
        $this->data['class_id'] = $classID;
        $this->data['subject_id'] = $subjectID;
        $this->data['chapter_id'] = $chapterID;
        $this->data['topic_id'] = $topicID;
        $this->data['type'] = $type;
        $this->data['difficulty'] = $difficulty;
        $this->data['taxonomy'] = $taxonomy;
        $this->data['status'] = $status;
        $this->data['search'] = $search;

        $this->data['stats'] = $this->question_bank_model->getRepositoryStats($branchID);
        $this->data['questions'] = $this->question_bank_model->getQuestionsList($filters);
        $this->data['types'] = $this->question_bank_model->getQuestionTypes();
        $this->data['difficulties'] = $this->question_bank_model->getDifficultyLevels();
        $this->data['taxonomies'] = $this->question_bank_model->getTaxonomyLevels();

        $this->data['classList'] = $this->app_lib->getClass($branchID);
        $this->data['subjectList'] = !empty($classID) ? $this->subject_model->getSubjectByClassSection($classID) : array();
        $this->data['chapterList'] = (!empty($classID) && !empty($subjectID)) ? $this->question_bank_model->getChapters($branchID, $classID, $subjectID) : array();

        $this->data['title'] = (function_exists('get_current_lang') && get_current_lang() === 'bn') ? 'প্রশ্ন ভাণ্ডার ও সংগ্রহশালা' : 'Question Bank Repository';
        $this->data['sub_page'] = 'question_bank/index';
        $this->data['main_menu'] = 'question_bank';

        $this->load->view('layout/index', $this->data);
    }

    /**
     * Add Question
     */
    public function question_add()
    {
        if (!get_permission('question_bank', 'is_add')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();

        $this->data['branch_id'] = $branchID;
        $this->data['types'] = $this->question_bank_model->getQuestionTypes();
        $this->data['difficulties'] = $this->question_bank_model->getDifficultyLevels();
        $this->data['taxonomies'] = $this->question_bank_model->getTaxonomyLevels();
        $this->data['classList'] = $this->app_lib->getClass($branchID);

        $this->data['title'] = (function_exists('get_current_lang') && get_current_lang() === 'bn') ? 'নতুন প্রশ্ন যোগ করুন' : 'Add Question';
        $this->data['sub_page'] = 'question_bank/question_add';
        $this->data['main_menu'] = 'question_bank';

        $this->load->view('layout/index', $this->data);
    }

    /**
     * Edit Question
     */
    public function question_edit($id = '')
    {
        if (!get_permission('question_bank', 'is_edit')) {
            access_denied();
        }

        $id = intval($id);
        $question = $this->question_bank_model->getQuestionDetails($id);
        if (!$question) {
            set_alert('error', (function_exists('get_current_lang') && get_current_lang() === 'bn') ? 'প্রশ্নটি পাওয়া যায়নি' : 'Question not found');
            redirect(base_url('question_bank'));
        }

        $branchID = $question['branch_id'];
        $this->data['branch_id'] = $branchID;
        $this->data['question'] = $question;
        $this->data['types'] = $this->question_bank_model->getQuestionTypes();
        $this->data['difficulties'] = $this->question_bank_model->getDifficultyLevels();
        $this->data['taxonomies'] = $this->question_bank_model->getTaxonomyLevels();
        $this->data['classList'] = $this->app_lib->getClass($branchID);
        $this->data['subjectList'] = $this->subject_model->getSubjectByClassSection($question['class_id']);
        $this->data['chapterList'] = $this->question_bank_model->getChapters($branchID, $question['class_id'], $question['subject_id']);
        $this->data['topicList'] = !empty($question['chapter_id']) ? $this->question_bank_model->getTopics($question['chapter_id']) : array();

        $this->data['title'] = (function_exists('get_current_lang') && get_current_lang() === 'bn') ? 'প্রশ্ন সম্পাদনা করুন' : 'Edit Question';
        $this->data['sub_page'] = 'question_bank/question_edit';
        $this->data['main_menu'] = 'question_bank';

        $this->load->view('layout/index', $this->data);
    }

    /**
     * Save Question (Add/Edit)
     */
    public function question_save()
    {
        if ($_POST) {
            $this->form_validation->set_rules('class_id', 'Class', 'trim|required|numeric');
            $this->form_validation->set_rules('subject_id', 'Subject', 'trim|required|numeric');
            $this->form_validation->set_rules('type', 'Question Type', 'trim|required');
            $this->form_validation->set_rules('question_text', 'Question Text', 'trim|required');
            $this->form_validation->set_rules('marks', 'Marks', 'trim|required|numeric');

            if ($this->form_validation->run() !== false) {
                $post = $this->input->post();
                $branchID = $this->application_model->get_branch_id();
                $questionId = $this->question_bank_model->saveQuestion($post, $branchID);

                $msg = (function_exists('get_current_lang') && get_current_lang() === 'bn') 
                    ? 'প্রশ্নটি সফলভাবে সংরক্ষণ করা হয়েছে' 
                    : 'Question saved successfully';

                if ($this->input->is_ajax_request()) {
                    echo json_encode(array('status' => 'success', 'message' => $msg, 'question_id' => $questionId));
                    exit;
                } else {
                    set_alert('success', $msg);
                    redirect(base_url('question_bank'));
                }
            } else {
                $error = $this->form_validation->error_array();
                if ($this->input->is_ajax_request()) {
                    echo json_encode(array('status' => 'fail', 'error' => $error));
                    exit;
                } else {
                    set_alert('error', implode('<br>', $error));
                    redirect($_SERVER['HTTP_REFERER']);
                }
            }
        }
    }

    /**
     * Delete Question
     */
    public function question_delete($id = '')
    {
        if (!get_permission('question_bank', 'is_delete')) {
            access_denied();
        }

        $id = intval($id);
        $this->question_bank_model->deleteQuestion($id);
        $msg = (function_exists('get_current_lang') && get_current_lang() === 'bn') 
            ? 'প্রশ্নটি সফলভাবে মুছে ফেলা হয়েছে' 
            : 'Question deleted successfully';
        set_alert('success', $msg);
        redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * Question Status Change (Approve / Reject)
     */
    public function question_status_change()
    {
        if (!is_superadmin_loggedin() && !is_admin_loggedin() && !get_permission('question_bank', 'is_edit')) {
            echo json_encode(array('status' => 'fail', 'message' => 'Unauthorized'));
            exit;
        }

        $id = intval($this->input->post('question_id'));
        $status = $this->input->post('status');

        if ($id && in_array($status, array('approved', 'rejected', 'review', 'draft'))) {
            $this->question_bank_model->updateQuestionStatus($id, $status);
            $msg = (function_exists('get_current_lang') && get_current_lang() === 'bn') 
                ? 'প্রশ্নের স্ট্যাটাস সফলভাবে আপডেট হয়েছে' 
                : 'Question status updated successfully';
            echo json_encode(array('status' => 'success', 'message' => $msg));
            exit;
        }

        echo json_encode(array('status' => 'fail', 'message' => 'Invalid parameters'));
        exit;
    }

    /**
     * Question Preview Modal Ajax
     */
    public function question_preview_ajax($id = '')
    {
        $id = intval($id);
        $question = $this->question_bank_model->getQuestionDetails($id);
        if (!$question) {
            echo '<div class="alert alert-danger">Question not found</div>';
            exit;
        }

        $data['question'] = $question;
        $data['types'] = $this->question_bank_model->getQuestionTypes();
        $data['difficulties'] = $this->question_bank_model->getDifficultyLevels();
        $data['taxonomies'] = $this->question_bank_model->getTaxonomyLevels();
        $this->load->view('question_bank/modal_preview', $data);
    }

    /**
     * AI Question Generator Page
     */
    public function ai_generator()
    {
        if (!get_permission('question_bank', 'is_add')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();

        $this->data['branch_id'] = $branchID;
        $this->data['ai_settings'] = $this->question_bank_model->getAiSettings($branchID);
        $this->data['types'] = $this->question_bank_model->getQuestionTypes();
        $this->data['difficulties'] = $this->question_bank_model->getDifficultyLevels();
        $this->data['taxonomies'] = $this->question_bank_model->getTaxonomyLevels();
        $this->data['classList'] = $this->app_lib->getClass($branchID);

        $this->data['title'] = (function_exists('get_current_lang') && get_current_lang() === 'bn') ? 'এআই প্রশ্ন জেনারেটর' : 'AI Question Generator Pro';
        $this->data['sub_page'] = 'question_bank/ai_generator';
        $this->data['main_menu'] = 'question_bank';

        $this->load->view('layout/index', $this->data);
    }

    /**
     * AI Generate Questions via Ajax
     */
    public function ai_generate_ajax()
    {
        if (!get_permission('question_bank', 'is_add')) {
            echo json_encode(array('status' => 'fail', 'message' => 'Unauthorized'));
            exit;
        }

        $branchID = $this->application_model->get_branch_id();
        $classID = $this->input->post('class_id');
        $subjectID = $this->input->post('subject_id');
        $topic = $this->input->post('topic');
        $type = $this->input->post('type');
        $quantity = $this->input->post('quantity');
        $difficulty = $this->input->post('difficulty');
        $taxonomy = $this->input->post('taxonomy');
        $language = $this->input->post('language');
        $sourceText = $this->input->post('source_text');

        // Fetch Class and Subject names
        $className = '';
        if ($classID) {
            $cRow = $this->db->where('id', $classID)->get('class')->row_array();
            $className = $cRow ? $cRow['name'] : '';
        }

        $subjectName = '';
        if ($subjectID) {
            $sRow = $this->db->where('id', $subjectID)->get('subject')->row_array();
            $subjectName = $sRow ? $sRow['name'] : '';
        }

        $params = array(
            'class_id' => $classID,
            'class_name' => $className,
            'subject_id' => $subjectID,
            'subject_name' => $subjectName,
            'topic' => $topic,
            'type' => $type,
            'quantity' => $quantity,
            'difficulty' => $difficulty,
            'taxonomy' => $taxonomy,
            'language' => $language,
            'source_text' => $sourceText
        );

        $res = $this->question_bank_model->generateQuestionsWithAI($params, $branchID);
        echo json_encode($res);
        exit;
    }

    /**
     * Import AI Generated Questions into Question Bank
     */
    public function ai_import_ajax()
    {
        if (!get_permission('question_bank', 'is_add')) {
            echo json_encode(array('status' => 'fail', 'message' => 'Unauthorized'));
            exit;
        }

        $branchID = $this->application_model->get_branch_id();
        $classID = intval($this->input->post('class_id'));
        $subjectID = intval($this->input->post('subject_id'));
        $chapterID = $this->input->post('chapter_id') ? intval($this->input->post('chapter_id')) : null;
        $questionsJson = $this->input->post('questions_json');

        if (empty($classID) || empty($subjectID) || empty($questionsJson)) {
            echo json_encode(array('status' => 'fail', 'message' => 'Invalid parameters'));
            exit;
        }

        $questions = json_decode($questionsJson, true);
        if (!is_array($questions) || empty($questions)) {
            echo json_encode(array('status' => 'fail', 'message' => 'No questions provided for import'));
            exit;
        }

        $imported = 0;
        foreach ($questions as $q) {
            $post = array(
                'class_id' => $classID,
                'subject_id' => $subjectID,
                'chapter_id' => $chapterID,
                'type' => !empty($q['type']) ? $q['type'] : 'mcq',
                'difficulty' => !empty($q['difficulty']) ? $q['difficulty'] : 'medium',
                'taxonomy' => !empty($q['taxonomy']) ? $q['taxonomy'] : 'remembering',
                'question_text' => $q['question_text'],
                'explanation' => !empty($q['explanation']) ? $q['explanation'] : null,
                'marks' => !empty($q['marks']) ? $q['marks'] : 1.00,
                'source' => 'ai_generated',
                'status' => 'approved'
            );

            if ($post['type'] === 'mcq' && !empty($q['options'])) {
                $post['mcq_options'] = array();
                $post['mcq_correct'] = 0;
                foreach ($q['options'] as $idx => $opt) {
                    $post['mcq_options'][] = $opt['text'];
                    if (!empty($opt['is_correct'])) {
                        $post['mcq_correct'] = $idx;
                    }
                }
            } elseif ($post['type'] === 'true_false') {
                $post['tf_correct'] = 'true';
                if (!empty($q['options'])) {
                    foreach ($q['options'] as $opt) {
                        if ($opt['key'] === 'false' && !empty($opt['is_correct'])) {
                            $post['tf_correct'] = 'false';
                        }
                    }
                }
            } elseif ($post['type'] === 'fill_blank' && !empty($q['fill_blanks'])) {
                $post['blank_answers'] = $q['fill_blanks'];
            } elseif ($post['type'] === 'matching' && !empty($q['match_pairs'])) {
                $post['match_left'] = array();
                $post['match_right'] = array();
                foreach ($q['match_pairs'] as $pair) {
                    $post['match_left'][] = $pair['left'];
                    $post['match_right'][] = $pair['right'];
                }
            } elseif (!empty($q['model_answer'])) {
                $post['model_answer'] = $q['model_answer'];
            }

            $this->question_bank_model->saveQuestion($post, $branchID);
            $imported++;
        }

        $msg = (function_exists('get_current_lang') && get_current_lang() === 'bn') 
            ? "সফলভাবে {$imported} টি প্রশ্ন প্রশ্ন ভাণ্ডারে যুক্ত করা হয়েছে" 
            : "Successfully imported {$imported} questions into the Question Bank";

        echo json_encode(array('status' => 'success', 'message' => $msg, 'count' => $imported));
        exit;
    }

    /**
     * Chapters & Topics Management View
     */
    public function chapters_topics()
    {
        if (!get_permission('question_bank', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        $classID = $this->input->get('class_id');
        $subjectID = $this->input->get('subject_id');

        $this->data['branch_id'] = $branchID;
        $this->data['class_id'] = $classID;
        $this->data['subject_id'] = $subjectID;
        $this->data['classList'] = $this->app_lib->getClass($branchID);
        $this->data['subjectList'] = !empty($classID) ? $this->subject_model->getSubjectByClassSection($classID) : array();
        $this->data['chapters'] = $this->question_bank_model->getChapters($branchID, $classID, $subjectID);

        $this->data['title'] = (function_exists('get_current_lang') && get_current_lang() === 'bn') ? 'অধ্যায় ও পাঠ্যসূচি ব্যবস্থাপনা' : 'Chapters & Topics Management';
        $this->data['sub_page'] = 'question_bank/chapters_topics';
        $this->data['main_menu'] = 'question_bank';

        $this->load->view('layout/index', $this->data);
    }

    /**
     * Save Chapter Ajax
     */
    public function chapter_save()
    {
        if ($_POST) {
            $this->form_validation->set_rules('class_id', 'Class', 'trim|required|numeric');
            $this->form_validation->set_rules('subject_id', 'Subject', 'trim|required|numeric');
            $this->form_validation->set_rules('name', 'Chapter Name', 'trim|required');

            if ($this->form_validation->run() !== false) {
                $post = $this->input->post();
                $branchID = $this->application_model->get_branch_id();
                $id = $this->question_bank_model->saveChapter($post, $branchID);

                $msg = (function_exists('get_current_lang') && get_current_lang() === 'bn') ? 'অধ্যায় সফলভাবে সংরক্ষিত হয়েছে' : 'Chapter saved successfully';
                set_alert('success', $msg);
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                set_alert('error', implode('<br>', $this->form_validation->error_array()));
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    public function chapter_delete($id = '')
    {
        $this->question_bank_model->deleteChapter(intval($id));
        $msg = (function_exists('get_current_lang') && get_current_lang() === 'bn') ? 'অধ্যায় মুছে ফেলা হয়েছে' : 'Chapter deleted successfully';
        set_alert('success', $msg);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function topic_save()
    {
        if ($_POST) {
            $this->form_validation->set_rules('chapter_id', 'Chapter', 'trim|required|numeric');
            $this->form_validation->set_rules('name', 'Topic Name', 'trim|required');

            if ($this->form_validation->run() !== false) {
                $post = $this->input->post();
                $this->question_bank_model->saveTopic($post);

                $msg = (function_exists('get_current_lang') && get_current_lang() === 'bn') ? 'টপিক সফলভাবে সংরক্ষিত হয়েছে' : 'Topic saved successfully';
                set_alert('success', $msg);
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                set_alert('error', implode('<br>', $this->form_validation->error_array()));
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    public function topic_delete($id = '')
    {
        $this->question_bank_model->deleteTopic(intval($id));
        $msg = (function_exists('get_current_lang') && get_current_lang() === 'bn') ? 'টপিক মুছে ফেলা হয়েছে' : 'Topic deleted successfully';
        set_alert('success', $msg);
        redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * Ajax endpoints for dependent dropdowns
     */
    public function get_chapters_by_subject()
    {
        $branchID = $this->application_model->get_branch_id();
        $classID = $this->input->post('class_id');
        $subjectID = $this->input->post('subject_id');

        $chapters = $this->question_bank_model->getChapters($branchID, $classID, $subjectID);
        $html = '<option value="">' . translate('select') . '</option>';
        foreach ($chapters as $ch) {
            $html .= '<option value="' . $ch['id'] . '">' . html_escape((!empty($ch['chapter_no']) ? $ch['chapter_no'] . ': ' : '') . $ch['name']) . '</option>';
        }
        echo $html;
        exit;
    }

    public function get_topics_by_chapter()
    {
        $chapterID = $this->input->post('chapter_id');
        $topics = $this->question_bank_model->getTopics($chapterID);
        $html = '<option value="">' . translate('select') . '</option>';
        foreach ($topics as $tp) {
            $html .= '<option value="' . $tp['id'] . '">' . html_escape($tp['name']) . '</option>';
        }
        echo $html;
        exit;
    }

    /**
     * Exam Paper Generator Wizard
     */
    public function paper_generator()
    {
        if (!get_permission('question_bank', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();

        $this->data['branch_id'] = $branchID;
        $this->data['classList'] = $this->app_lib->getClass($branchID);
        $this->data['types'] = $this->question_bank_model->getQuestionTypes();
        $this->data['difficulties'] = $this->question_bank_model->getDifficultyLevels();
        $this->data['taxonomies'] = $this->question_bank_model->getTaxonomyLevels();

        $this->data['title'] = (function_exists('get_current_lang') && get_current_lang() === 'bn') ? 'স্বয়ংক্রিয় প্রশ্নপত্র প্রণয়ন' : 'Automated Question Paper Generator';
        $this->data['sub_page'] = 'question_bank/paper_generator';
        $this->data['main_menu'] = 'question_bank';

        $this->load->view('layout/index', $this->data);
    }

    /**
     * Automated Paper Generate Preview Ajax
     */
    public function paper_generate_preview_ajax()
    {
        $branchID = $this->application_model->get_branch_id();
        $params = $this->input->post();

        $sections = $this->question_bank_model->generateAutomatedPaper($params, $branchID);
        echo json_encode(array('status' => 'success', 'sections' => $sections));
        exit;
    }

    /**
     * Save Generated Paper
     */
    public function paper_save()
    {
        if ($_POST) {
            $this->form_validation->set_rules('class_id', 'Class', 'trim|required|numeric');
            $this->form_validation->set_rules('subject_id', 'Subject', 'trim|required|numeric');
            $this->form_validation->set_rules('title', 'Exam Title', 'trim|required');
            $this->form_validation->set_rules('total_marks', 'Total Marks', 'trim|required|numeric');
            $this->form_validation->set_rules('duration_minutes', 'Duration', 'trim|required|numeric');

            if ($this->form_validation->run() !== false) {
                $post = $this->input->post();
                $branchID = $this->application_model->get_branch_id();
                $paperId = $this->question_bank_model->savePaper($post, $branchID);

                $msg = (function_exists('get_current_lang') && get_current_lang() === 'bn') ? 'প্রশ্নপত্র সফলভাবে তৈরি হয়েছে' : 'Question paper created successfully';
                set_alert('success', $msg);
                redirect(base_url('question_bank/paper_view/' . $paperId));
            } else {
                set_alert('error', implode('<br>', $this->form_validation->error_array()));
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    /**
     * List of Generated Exam Papers
     */
    public function papers_list()
    {
        if (!get_permission('question_bank', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        $classID = $this->input->get('class_id');
        $subjectID = $this->input->get('subject_id');

        $this->data['branch_id'] = $branchID;
        $this->data['class_id'] = $classID;
        $this->data['subject_id'] = $subjectID;
        $this->data['classList'] = $this->app_lib->getClass($branchID);
        $this->data['subjectList'] = !empty($classID) ? $this->subject_model->getSubjectByClassSection($classID) : array();
        $this->data['papers'] = $this->question_bank_model->getPaperList($branchID, $classID, $subjectID);

        $this->data['title'] = (function_exists('get_current_lang') && get_current_lang() === 'bn') ? 'তৈরিকৃত প্রশ্নপত্রের তালিকা' : 'Generated Question Papers';
        $this->data['sub_page'] = 'question_bank/papers_list';
        $this->data['main_menu'] = 'question_bank';

        $this->load->view('layout/index', $this->data);
    }

    /**
     * Printable Question Paper View (A4 Layout + Answer Key)
     */
    public function paper_view($id = '')
    {
        if (!get_permission('question_bank', 'is_view')) {
            access_denied();
        }

        $id = intval($id);
        $paper = $this->question_bank_model->getPaperDetails($id);
        if (!$paper) {
            set_alert('error', (function_exists('get_current_lang') && get_current_lang() === 'bn') ? 'প্রশ্নপত্র পাওয়া যায়নি' : 'Question paper not found');
            redirect(base_url('question_bank/papers_list'));
        }

        $this->data['paper'] = $paper;
        $this->data['title'] = $paper['title'];
        $this->data['sub_page'] = 'question_bank/paper_view';
        $this->data['main_menu'] = 'question_bank';

        $this->load->view('layout/index', $this->data);
    }

    /**
     * Delete Exam Paper
     */
    public function paper_delete($id = '')
    {
        if (!get_permission('question_bank', 'is_delete')) {
            access_denied();
        }

        $id = intval($id);
        $this->question_bank_model->deletePaper($id);
        $msg = (function_exists('get_current_lang') && get_current_lang() === 'bn') ? 'প্রশ্নপত্র মুছে ফেলা হয়েছে' : 'Question paper deleted successfully';
        set_alert('success', $msg);
        redirect(base_url('question_bank/papers_list'));
    }

    /**
     * Review & Approval Workflow Queue
     */
    public function approvals()
    {
        if (!is_superadmin_loggedin() && !is_admin_loggedin() && !get_permission('question_bank', 'is_edit')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        $filters = array(
            'branch_id' => $branchID,
            'status' => 'review'
        );

        $this->data['branch_id'] = $branchID;
        $this->data['questions'] = $this->question_bank_model->getQuestionsList($filters);
        $this->data['types'] = $this->question_bank_model->getQuestionTypes();
        $this->data['difficulties'] = $this->question_bank_model->getDifficultyLevels();
        $this->data['taxonomies'] = $this->question_bank_model->getTaxonomyLevels();

        $this->data['title'] = (function_exists('get_current_lang') && get_current_lang() === 'bn') ? 'প্রশ্ন পর্যালোচনা ও অনুমোদন' : 'Question Review & Approval';
        $this->data['sub_page'] = 'question_bank/approvals';
        $this->data['main_menu'] = 'question_bank';

        $this->load->view('layout/index', $this->data);
    }

    /**
     * Settings Page (AI Provider, API Keys, Header Options)
     */
    public function settings()
    {
        if (!is_superadmin_loggedin() && !is_admin_loggedin()) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();

        if ($_POST) {
            $post = $this->input->post();
            $this->question_bank_model->saveAiSettings($post, $branchID);

            $msg = (function_exists('get_current_lang') && get_current_lang() === 'bn') ? 'সেটিংস সফলভাবে সংরক্ষিত হয়েছে' : 'Settings saved successfully';
            set_alert('success', $msg);
            redirect(base_url('question_bank/settings'));
        }

        $this->data['branch_id'] = $branchID;
        $this->data['settings'] = $this->question_bank_model->getAiSettings($branchID);

        $this->data['title'] = (function_exists('get_current_lang') && get_current_lang() === 'bn') ? 'প্রশ্ন ভাণ্ডার ও এআই কনফিগারেশন' : 'Question Bank & AI Settings';
        $this->data['sub_page'] = 'question_bank/settings';
        $this->data['main_menu'] = 'question_bank';

        $this->load->view('layout/index', $this->data);
    }
}
