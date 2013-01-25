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
        // Set the super object to a local variable for use throughout the class
        $CI =& get_instance();
        
        // 로봇은 세션 생성 안함
        $CI->load->library('user_agent');
        if ($CI->agent->is_robot()) {
            return;
        }

        parent::__construct();
    }
}
