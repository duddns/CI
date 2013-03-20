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
 * Output Class
 *
 * Responsible for sending final output to browser
 *
 * @package        CodeIgniter
 * @subpackage    Libraries
 * @category    Output
 * @author        ExpressionEngine Dev Team
 * @link        http://codeigniter.com/user_guide/libraries/output.html
 */
class MY_Output extends CI_Output {

    /**
     * Display Output
     *
     * All "view" data is automatically put into this variable by the controller class:
     *
     * $this->final_output
     *
     * This function sends the finalized output data to the browser along
     * with any server headers and profile data.  It also stops the
     * benchmark timer so the page rendering speed and memory usage can be shown.
     *
     * @access    public
     * @param     string
     * @return    mixed
     */
    function _display($output = '')
    {
        // Note:  We use globals because we can't use $CI =& get_instance()
        // since this function is sometimes called by the caching mechanism,
        // which happens before the CI super object is available.
        global $BM, $CFG;

        // Grab the super object if we can.
        if (class_exists('CI_Controller'))
        {
            $CI =& get_instance();
        }

        // --------------------------------------------------------------------

        // Set the output data
        if ($output == '')
        {
            $output =& $this->final_output;
        }

        // --------------------------------------------------------------------

        // Do we need to write a cache file?  Only if the controller does not have its
        // own _output() method and we are not dealing with a cache file, which we
        // can determine by the existence of the $CI object above
        if ($this->cache_expiration > 0 && isset($CI) && ! method_exists($CI, '_output'))
        {
            $this->_write_cache($output);
        }

        // --------------------------------------------------------------------

        // Parse out the elapsed time and memory usage,
        // then swap the pseudo-variables with the data

        $elapsed = $BM->elapsed_time('total_execution_time_start', 'total_execution_time_end');

        if ($this->parse_exec_vars === TRUE)
        {
            $memory     = ( ! function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2).'MB';

            $output = str_replace('{elapsed_time}', $elapsed, $output);
            $output = str_replace('{memory_usage}', $memory, $output);
        }

        // --------------------------------------------------------------------

        // Is compression requested?
        if ($CFG->item('compress_output') === TRUE && $this->_zlib_oc == FALSE)
        {
            if (extension_loaded('zlib'))
            {
                if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) AND strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== FALSE)
                {
                    ob_start('ob_gzhandler');
                }
            }
        }

        // --------------------------------------------------------------------

        // Are there any server headers to send?
        if (count($this->headers) > 0)
        {
            foreach ($this->headers as $header)
            {
                @header($header[0], $header[1]);
            }
        }

        // --------------------------------------------------------------------

        // Does the $CI object exist?
        // If not we know we are dealing with a cache file so we'll
        // simply echo out the data and exit.
        if ( ! isset($CI))
        {
            echo $output;
            log_message('debug', "Final output sent to browser");
            log_message('debug', "Total execution time: ".$elapsed);
            return TRUE;
        }

        // --------------------------------------------------------------------

        // Do we need to generate profile data?
        // If so, load the Profile class and run it.
        if ($this->enable_profiler == TRUE)
        {
            // ajax 요청이면 프로파일 결과를 firephp 에 출력하기 위해, 수정된 라이브러리를 load 합니다. 
            if ($CI->input->is_ajax_request()) {
                $CI->load->library('Profiler_for_FirePHP', '', 'profiler'); 
            } else {
                $CI->load->library('profiler');
            }

            if ( ! empty($this->_profiler_sections))
            {
                $CI->profiler->set_sections($this->_profiler_sections);
            }

            // If the output data contains closing </body> and </html> tags
            // we will remove them and add them back after we insert the profile data
            if (preg_match("|</body>.*?</html>|is", $output))
            {
                $output  = preg_replace("|</body>.*?</html>|is", '', $output);
                $output .= $CI->profiler->run();
                $output .= '</body></html>';
            }
            else
            {
                $output .= $CI->profiler->run();
            }
        }

        // --------------------------------------------------------------------

        // Does the controller contain a function named _output()?
        // If so send the output there.  Otherwise, echo it.
        if (method_exists($CI, '_output'))
        {
            $CI->_output($output);
        }
        else
        {
            echo $output;  // Send it to the browser!
        }

        log_message('debug', "Final output sent to browser");
        log_message('debug', "Total execution time: ".$elapsed);
    }
}
// END Output Class

/* End of file Output.php */
/* Location: ./system/core/Output.php */