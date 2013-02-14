<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package        CodeIgniter
 * @author        ExpressionEngine Dev Team
 * @copyright    Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license        http://codeigniter.com/user_guide/license.html
 * @link        http://codeigniter.com
 * @since        Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Session Class
 *
 * @package        CodeIgniter
 * @subpackage    Libraries
 * @category    Sessions
 * @author        ExpressionEngine Dev Team
 * @link        http://codeigniter.com/user_guide/libraries/sessions.html
 */
class MY_Session extends CI_Session {

    /**
	 * Session Constructor
	 *
	 * The constructor runs the session routines automatically
	 * whenever the class is instantiated.
	 */
	public function __construct($params = array())
	{
	    /*
	     * 로봇은 세션 생성 안하도록
	     */
	    
		$CI =& get_instance();
		
		$CI->load->library('user_agent');
		if (!$CI->user_agent->is_robot()) {
		    parent::__construct();
		}
	}
	
    // --------------------------------------------------------------------

    /**
     * sess_update()
     *
     * Do not update an existing session on ajax or xajax calls
     *
     * @access    public
     * @return    void
     */
    public function sess_update()
    {
        /*
         * ajax 요청을 경우에 세션이 사라지는 문제 수정
         */
        
        $CI =& get_instance();

        if (!$CI->input->is_ajax_request()) {
            parent::sess_update();
        }
    }
}
