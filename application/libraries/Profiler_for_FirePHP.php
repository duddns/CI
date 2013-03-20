<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Profiler for FirePHP
 * taken from CodeIgniter Profiler Class
 * 
 * @author aprilbriz@gmail.com (http://aprilbriz.com)
 */

class Profiler_for_FirePHP {

	var $CI;
	var $FP;
 	
 	public function __construct()
 	{
 		$this->CI =& get_instance();
 		$this->CI->load->language('profiler');
		$this->CI->load->library('FirePHP');
		$this->FP =& FirePHP::getInstance(true);
 	}
 	
	// --------------------------------------------------------------------

	/**
	 * Auto Profiler
	 *
	 * This function cycles through the entire array of mark points and
	 * matches any two points that are named identically (ending in "_start"
	 * and "_end" respectively).  It then compiles the execution times for
	 * all points and returns it as an array
	 *
	 * @access	private
	 * @return	array
	 */
 	function _compile_benchmarks()
 	{
  		$profile = array();
 		foreach ($this->CI->benchmark->marker as $key => $val)
 		{
 			// We match the "end" marker so that the list ends
 			// up in the order that it was defined
 			if (preg_match("/(.+?)_end/i", $key, $match))
 			{ 			
 				if (isset($this->CI->benchmark->marker[$match[1].'_end']) AND isset($this->CI->benchmark->marker[$match[1].'_start']))
 				{
 					$profile[$match[1]] = $this->CI->benchmark->elapsed_time($match[1].'_start', $key);
 				}
 			}
 		}

		// Build a table containing the profile data.
		// Note: At some point we should turn this into a template that can
		// be modified.  We also might want to make this data available to be logged
		$output_title = $this->CI->lang->line('profiler_benchmarks');
		$output = array();
		$output[] = array('', '');
		foreach ($profile as $key => $val)
		{
			$key = ucwords(str_replace(array('_', '-'), ' ', $key));
			$output[] = array($key, $val);
		}
		
		$this->FP->table($output_title, $output);
 	}
 	
	// --------------------------------------------------------------------

	/**
	 * Compile Queries
	 *
	 * @access	private
	 * @return	string
	 */	
	function _compile_queries()
	{
		$dbs = array();

		// Let's determine which databases are currently connected to
		foreach (get_object_vars($this->CI) as $CI_object)
		{
			if (is_object($CI_object) && is_subclass_of(get_class($CI_object), 'CI_DB') )
			{
				$dbs[] = $CI_object;
			}
		}
		
		$output_title = '';
		$output = array();
					
		if (count($dbs) == 0)
		{
			$output_title = $this->CI->lang->line('profiler_queries');
			$output[] = array('');
			$output[] = array($this->CI->lang->line('profiler_no_db'));
			
			$this->FP->table($output_title, $output);
			return;
		}
		
		foreach ($dbs as $db)
		{
			$output_title = $this->CI->lang->line('profiler_database').': '.$db->database.' '.$this->CI->lang->line('profiler_queries').': '.count($this->CI->db->queries);
			
			if (count($db->queries) == 0)
			{
				$output[] = array('');
				$output[] = array($this->CI->lang->line('profiler_no_queries'));
			}
			else
			{				
				$output[] = array('', '');
				foreach ($db->queries as $key => $val)
				{					
					$time = number_format($db->query_times[$key], 4);
					$output[] = array($time, $val);
				}
			}
		}
		
		$this->FP->table($output_title, $output);
	}

	
	// --------------------------------------------------------------------

	/**
	 * Compile $_GET Data
	 *
	 * @access	private
	 * @return	string
	 */	
	function _compile_get()
	{	
		$output_title = $this->CI->lang->line('profiler_get_data');
		$output = array();
				
		if (count($_GET) == 0)
		{
			$output[] = array('');
			$output[] = array($this->CI->lang->line('profiler_no_get'));
		}
		else
		{
			$output[] = array('', '');
		
			foreach ($_GET as $key => $val)
			{
				if ( ! is_numeric($key))
				{
					$key = "'".$key."'";
				}

				if (is_array($val))
				{
					$val = htmlspecialchars(stripslashes(print_r($val, true)));
				}
				else
				{
					$val = htmlspecialchars(stripslashes($val));
				}
				$output[] = array('$_GET['.$key.']', $val);
			}
		}

		$this->FP->table($output_title, $output);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Compile $_POST Data
	 *
	 * @access	private
	 * @return	string
	 */	
	function _compile_post()
	{	
		$output_title = $this->CI->lang->line('profiler_post_data');
		$output = array();

		if (count($_POST) == 0)
		{
			$output[] = array('');
			$output[] = array($this->CI->lang->line('profiler_no_post'));
		}
		else
		{
			$output[] = array('', '');
		
			foreach ($_POST as $key => $val)
			{
				if ( ! is_numeric($key))
				{
					$key = "'".$key."'";
				}
			
				if (is_array($val))
				{
					$val = htmlspecialchars(stripslashes(print_r($val, true)));
				}
				else
				{
					$val = htmlspecialchars(stripslashes($val));
				}
				$output[] = array('$_POST['.$key.']', $val);
			}
		}

		$this->FP->table($output_title, $output);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Show query string
	 *
	 * @access	private
	 * @return	string
	 */	
	function _compile_uri_string()
	{	
		$output_title = $this->CI->lang->line('profiler_uri_string');
		$output = array();
		$output[] = array('');
		
		if ($this->CI->uri->uri_string == '')
		{
			$output[] = array($this->CI->lang->line('profiler_no_uri'));
		}
		else
		{
			$output[] = array($this->CI->uri->uri_string);
		}

		$this->FP->table($output_title, $output);
	}

	// --------------------------------------------------------------------
	
	/**
	 * Show the controller and function that were called
	 *
	 * @access	private
	 * @return	string
	 */	
	function _compile_controller_info()
	{	
		$output_title = $this->CI->lang->line('profiler_controller_info');
		$output = array();
		$output[] = array('');
		$output[] = array($this->CI->router->fetch_directory().$this->CI->router->fetch_class()."/".$this->CI->router->fetch_method());

		$this->FP->table($output_title, $output);
	}
	// --------------------------------------------------------------------
	
	/**
	 * Compile memory usage
	 *
	 * Display total used memory
	 *
	 * @access	public
	 * @return	string
	 */
	function _compile_memory_usage()
	{
		$output_title = $this->CI->lang->line('profiler_memory_usage');
		$output = array();
		$output[] = array('');
		
		if (function_exists('memory_get_usage') && ($usage = memory_get_usage()) != '')
		{
			$output[] = array(number_format($usage) . ' bytes');
		}
		else
		{
			$output[] = array($this->CI->lang->line('profiler_no_memory_usage'));
		}
		
		$this->FP->table($output_title, $output);
	}

	// --------------------------------------------------------------------
	
	/**
	 * Run the Profiler
	 *
	 * @access	private
	 * @return	string
	 */	
	function run()
	{
		$this->_compile_uri_string();
		$this->_compile_controller_info();
		$this->_compile_memory_usage();
		$this->_compile_benchmarks();
		$this->_compile_get();
		$this->_compile_post();
		$this->_compile_queries();
	}

}

/* End of file */