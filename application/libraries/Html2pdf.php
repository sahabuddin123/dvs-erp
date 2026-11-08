<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . '/third_party/mpdf/autoload.php';

class Html2pdf
{
    public $mpdf;
    protected $CI;

    public function __construct($config = array())
    {
        $this->CI = &get_instance();
        $this->initialize($config);
    }

    public function initialize($config = array())
    {
        $tempDir = FCPATH . 'uploads/temp/mpdf';
        if (!is_dir($tempDir)) {
            @mkdir($tempDir, 0777, true);
        }

        $defaults = array(
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 6,
            'margin_right' => 6,
            'margin_top' => 8,
            'margin_bottom' => 8,
            'tempDir' => $tempDir,
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'useSubstitutions' => true
        );

        $settings = array_merge($defaults, $config);
        $this->mpdf = new \Mpdf\Mpdf($settings);
        $this->mpdf->autoScriptToLang = true;
        $this->mpdf->baseScript = 1;
        $this->mpdf->autoVietnamese = true;
        $this->mpdf->autoArabic = true;
    }

    public function writeHTML($html)
    {
        if ($this->mpdf) {
            $this->mpdf->WriteHTML($html);
        }
    }

    public function output($filename = 'document.pdf', $dest = 'I')
    {
        if ($this->mpdf) {
            return $this->mpdf->Output($filename, $dest);
        }
    }

    public function create($html, $filename = 'document.pdf', $dest = 'D')
    {
        $this->writeHTML($html);
        return $this->output($filename, $dest);
    }
}
