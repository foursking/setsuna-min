<?php

namespace Setsuna\Support;


class Input
{
    
	/**
	* Fetch an item from the GET array
	*
	* @access	public
	* @param	string
	* @param	bool
	* @return	string
	*/
	public static function get($index = NULL, $xss_clean = FALSE)
	{
		// Check if a field has been provided
		if ($index === NULL AND ! empty($_GET))
		{
			$get = array();

			// loop through the full _GET array
			foreach (array_keys($_GET) as $key)
			{
				$get[$key] = self::_fetch_from_array($_GET, $key, $xss_clean);
			}
			return $get;
		}

		return self::_fetch_from_array($_GET, $index, $xss_clean);
	}


    public static function raw_post()
    {
        $data = file_get_contents('php://input' , 'r');
        return $data;
    }


	/**
	* Fetch an item from the POST array
	*
	* @access	public
	* @param	string
	* @param	bool
	* @return	string
	*/
	public static function post($index = NULL, $xss_clean = FALSE)
	{
		// Check if a field has been provided
		if ($index === NULL AND ! empty($_POST))
		{
			$post = array();

			// Loop through the full _POST array and return it
			foreach (array_keys($_POST) as $key)
			{
				$post[$key] = self::_fetch_from_array($_POST, $key, $xss_clean);
			}
			return $post;
		}

		return self::_fetch_from_array($_POST, $index, $xss_clean);
	}


// --------------------------------------------------------------------

	/**
	* Fetch an item from either the GET array or the POST
	*
	* @access	public
	* @param	string	The index key
	* @param	bool	XSS cleaning
	* @return	string
	*/
	public static function get_post($index = '', $xss_clean = FALSE)
	{
		if ( ! isset($_POST[$index]) )
		{
			return self::get($index, $xss_clean);
		}
		else
		{
			return self::post($index, $xss_clean);
		}
	}




	// --------------------------------------------------------------------

	/**
	 * Fetch from array
	 *
	 * This is a helper function to retrieve values from global arrays
	 *
	 * @access	private
	 * @param	array
	 * @param	string
	 * @param	bool
	 * @return	string
	 */
	protected static function _fetch_from_array(&$array, $index = '', $xss_clean = FALSE)
	{
		if ( ! isset($array[$index])) {
			return FALSE;
		}

		if (TRUE === $xss_clean) {
			return $this->security->xss_clean($array[$index]);
		}

		return $array[$index];
	}

}


