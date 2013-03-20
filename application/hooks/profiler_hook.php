<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Profiler_hook {
    
    public function enable()
    {
        $CI =& get_instance();
        $CI->load->library('user_agent');
        if (!$CI->user_agent->is_mobile()) {
            $CI->output->enable_profiler(true);
        }
    }
}