<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ckeditor extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
        
        
        $this->load->helper('url');
    }
    

    public function index()
    {
        $this->load->view('ckeditor/index');
    }
    
    public function save()
    {
        echo '1';
    }
    
    public function fileupload()
    {
        $config['upload_path'] = $this->_dir_path();
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $this->load->library('upload', $config);
        
        // file_logo
        if (!$this->upload->do_upload('image')) {
            //log_message('error', $this->upload->display_errors('', ''));
            $error = array('error' => $this->upload->display_errors());
            
            echo json_encode(array($error));
        } else {
            $file = $this->upload->data();
            
            $info['name'] = $file['file_name'];
            $info['size'] = $file['file_size'];
            $info['type'] = $file['file_type'];
            $info['url'] = $this->_file_url($file['file_name']);
            $info['thumbnail_url'] = $this->_file_url($file['file_name']);
            $info['delete_url'] = $this->_delete_url();
            $info['delete_type'] = 'DELETE';
            
            echo json_encode(array('files' => array($info)));
        }
    }
    
    public function filebrowse()
    {
        $files = array();
        
        $this->load->helper('file');
        $filenames = get_filenames($this->_dir_path());
        if ($filenames) {
            sort($filenames);
            foreach ($filenames as $filename) {
                $url = $this->_file_url($filename);
                $files[] = array(
                    'name' => $filename, 
                    'size' =>filesize($this->_file_path($filename)),
                    'url' => $this->_file_url($filename),
                    'delete' => $this->_delete_url()
                );
            }
        }
        
        echo json_encode(array('files' => $files));
    }
    
    public function filedelete()
    {
        $file = $this->input->post('file');
        
        $this->load->helper('security');
        $file = sanitize_filename($file);
        
        $dir_path = $this->_dir_path();
        @unlink("{$dir_path}{$file}");
        
        echo json_encode(array('result' => 'success'));
    }
    
    
    private function _dir_path()
    {
        return HOMEPATH."files/ckeditor/";
    }
    
    private function _file_path($filename)
    {
        $dir_path = $this->_dir_path();
        return "{$dir_path}{$filename}";
    }
    
    private function _file_url($filename)
    {
        return "/files/ckeditor/{$filename}";
    }
    
    private function _delete_url()
    {
        return "/ckeditor/filedelete";
    }
}
