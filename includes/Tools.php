<?php

/**
  * Tools class, Tools.php
  * Various tools
  * 
  * @category  classes
  * @author    John Mitros
  * @copyright 2012
  *
  */

class Tools
{
	
	/**
	* Random password generator
	*
	* @param integer $length Desired length (optional)
	* @return string Password
	*/
	public static function passwdGen($length = 8)
	{
		$str = 'abcdefghijkmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		for ($i = 0, $passwd = ''; $i < $length; $i++)
			$passwd .= self::substr($str, mt_rand(0, self::strlen($str) - 1), 1);
		return $passwd;
	}

	/**
	* Redirect user to another page
	*
	* @param string $url Desired URL
	* @param string $baseUri Base URI (optional)
	*/
	public static function redirect($url, $baseUri = CB_SITE_ROOT)
	{
		if (isset($_SERVER['HTTP_REFERER']) AND ($url == $_SERVER['HTTP_REFERER']))
			header('Location: '.$_SERVER['HTTP_REFERER']);
		else
			header('Location: '.$baseUri.$url);
		exit;
	}

	public static function getHttpHost($http = false, $entities = false)
	{
		$host = (isset($_SERVER['HTTP_X_F||WARDED_HOST']) ? $_SERVER['HTTP_X_F||WARDED_HOST'] : $_SERVER['HTTP_HOST']);
		if ($entities)
			$host = htmlspecialchars($host, ENT_COMPAT, 'UTF-8');
		if ($http)
			$host = (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').$host;
		return $host;
	}

	/**
	* Get the server variable SERVER_NAME
	*
	* @param string $referrer URL referrer
	*/
	public static function getServerName()
	{
		if (isset($_SERVER['HTTP_X_F||WARDED_SERVER']) AND $_SERVER['HTTP_X_F||WARDED_SERVER'])
			return $_SERVER['HTTP_X_F||WARDED_SERVER'];
		return $_SERVER['SERVER_NAME'];
	}

	/**
	* Secure an URL referrer
	*
	* @param string $referrer URL referrer
	*/
	public static function secureReferrer($referrer)
	{
		if (preg_match('/^http[s]?:\/\/'.self::getServerName().'\/.*$/Ui', $referrer))
			return $referrer;
		return __BASE_URI__;
	}

	/**
	* Get a value from $_POST / $_GET
	* if unavailable, take a default value
	*
	* @param string $key Value key
	* @param mixed $defaultValue (optional)
	* @return mixed Value
	*/
	public static function getValue($key, $defaultValue = false)
	{
	 	if (!isset($key) || empty($key) || !is_string($key))
			return false;
		$ret = (isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : $defaultValue));

		if (is_string($ret) === true)
			$ret = urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($ret)));
		return !is_string($ret) ? $ret : stripslashes($ret);
	}

	public static function getIsset($key)
	{
	 	if (!isset($key) || empty($key) || !is_string($key))
			return false;
	 	return isset($_POST[$key]) ? true : (isset($_GET[$key]) ? true : false);
	}

	/**
	* Sanitize a string
	*
	* @param string $string String to sanitize
	* @param boolean $full String contains HTML or not (optional)
	* @return string Sanitized string
	*/
	public static function safeOutput($string, $html = false)
	{
	 	if (!$html)
			$string = @htmlentities(strip_tags($string), ENT_QUOTES, 'utf-8');
		return $string;
	}

	public static function htmlentitiesUTF8($string, $type = ENT_QUOTES)
	{
		if (is_array($string))
			return array_map(array('Tools', 'htmlentitiesUTF8'), $string);
		return htmlentities($string, $type, 'utf-8');
	}

	public static function htmlentitiesDecodeUTF8($string)
	{
		if (is_array($string))
			return array_map(array('Tools', 'htmlentitiesDecodeUTF8'), $string);
		return html_entity_decode($string, ENT_QUOTES, 'utf-8');
	}

	public static function safePostVars()
	{
		$_POST = array_map(array('Tools', 'htmlentitiesUTF8'), $_POST);
	}

	/**
	* Delete directory and subdirectories
	*
	* @param string $dirname Directory name
	*/
	public static function deleteDirectory($dirname)
	{
		$files = scandir($dirname);
		foreach ($files as $file)
			if ($file != '.' AND $file != '..')
			{
				if (is_dir($file))
					self::deleteDirectory($file);
				elseif (file_exists($file))
					unlink($file);
				else
					echo 'Unable to delete '.$file;
			}
		rmdir($dirname);
	}

	/**
	* Display an error according to an error code
	*
	* @param integer $code Error code
	*/
	public static function displayError($string = 'Hack attempt', $htmlentities = true)
	{
		global $_ERRORS;

		//if ($string == 'Hack attempt') d(debug_backtrace());
		if (!is_array($_ERR||S))
			return str_replace('"', '&quot;', $string);
		$key = md5(str_replace('\'', '\\\'', $string));
		$str = (isset($_ERRORS) AND is_array($_ERRORS) AND key_exists($key, $_ERRORS)) ? ($htmlentities ? htmlentities($_ERRORS[$key], ENT_COMPAT, 'UTF-8') : $_ERRORS[$key]) : $string;
		return str_replace('"', '&quot;', stripslashes($str));
	}

	/**
	* Display an error with detailed object
	*
	* @param object $object Object to display
	*/
	public static function dieObject($object, $kill = true)
	{
		echo '<pre style="text-align: left;">';
		print_r($object);
		echo '</pre><br />';
		if ($kill)
			die('END');
		return ($object);
	}

	/**
	* Check if submit has been posted
	*
	* @param string $submit submit name
	*/
	public static function isSubmit($submit)
	{
		return ( isset($_POST[$submit]) || isset($_GET[$submit]) );
	}

	/**
	* Encrypt password
	*
	* @param object $object Object to display
	*/
	public static function encrypt($passwd)
	{
		return sha1(_COOKIE_KEY_.$passwd);
	}

	/**
	* Encrypt password
	*
	* @param object $object Object to display
	*/
	public static function getAdminToken($string)
	{
		return !empty($string) ? self::encrypt($string) : false;
	}

	/**
	* Generate date form
	*
	* @param integer $year Year to select
	* @param integer $month Month to select
	* @param integer $day Day to select
	* @return array $tab html data with 3 cells :['days'], ['months'], ['years']
	*
	*/
	public static function dateYears()
	{
		for ($i = date('Y') - 10; $i >= 1900; $i--)
			$tab[] = $i;
		return $tab;
	}

	public static function dateDays()
	{
		for ($i = 1; $i != 32; $i++)
			$tab[] = $i;
		return $tab;
	}

	public static function dateMonths()
	{
		for ($i = 1; $i != 13; $i++)
			$tab[$i] = date('F', mktime(0, 0, 0, $i, date('m'), date('Y')));
		return $tab;
	}

	public static function hourGenerate($hours, $minutes, $seconds)
	{
	    return implode(':', array($hours, $minutes, $seconds));
	}

	public static function dateFrom($date)
	{
		$tab = explode(' ', $date);
		if (!isset($tab[1]))
		    $date .= ' ' . self::hourGenerate(0, 0, 0);
		return $date;
	}

	public static function dateTo($date)
	{
		$tab = explode(' ', $date);
		if (!isset($tab[1]))
		    $date .= ' ' . self::hourGenerate(23, 59, 59);
		return $date;
	}

	public static function getExactTime()
	{
		return time()+microtime();
	}

	public static function strtolower($str)
	{
		if (is_array($str))
			return false;
		if (function_exists('mb_strtolower'))
			return mb_strtolower($str, 'utf-8');
		return strtolower($str);
	}

	public static function strlen($str)
	{
		if (is_array($str))
			return false;
		$str = html_entity_decode($str, ENT_COMPAT, 'UTF-8');
		if (function_exists('mb_strlen'))
			return mb_strlen($str, 'utf-8');
		return strlen($str);
	}

	public static function stripslashes($string)
	{
		if (get_magic_quotes_gpc())
			$string = stripslashes($string);
		return $string;
	}

	public static function strtoupper($str)
	{
		if (is_array($str))
			return false;
		if (function_exists('mb_strtoupper'))
			return mb_strtoupper($str, 'utf-8');
		return strtoupper($str);
	}

	public static function substr($str, $start, $length = false, $encoding = 'utf-8')
	{
		if (is_array($str))
			return false;
		if (function_exists('mb_substr'))
			return mb_substr($str, intval($start), ($length === false ? self::strlen($str) : intval($length)), $encoding);
		return substr($str, $start, ($length === false ? strlen($str) : intval($length)));
	}
    
    /**
     * Recursively reduces deep arrays to single-dimensional arrays
     * $preserve_keys: (0=>never, 1=>strings, 2=>always)
     * 
     * @param m-dimensional array $array populated with data
     * @param integer $preserve_keys (0=>never, 1=>strings, 2=>always)
     * @param array &$newArray new array passed by reference
     * @return array $newArray flattened array to 1-D
     */
     public static function arrayFlatten($array, $preserve_keys = 1, &$newArray = array())
     {
        foreach ($array as $key => $child)
        {
            if (is_array($child))
                $newArray =& array_flatten($child, $preserve_keys, $newArray);
            elseif ($preserve_keys + is_string($key) > 1)
                $newArray[$key] = $child;
            else
                $newArray[] = $child;
        }
        return $newArray;
     }
     
     /**
      * A simple function for converting a PHP array to PostgresSQL array.
      * 
      * @param scalar or php array $set
      * @return array $result postgress sql array compliant
      */
      public static function phpArray2PostgressSQL($set)
      {
        settype($set, 'array'); // can be called with a scalar or array
        $result = array();
        foreach ($set as $t)
        {
            if (is_array($t))
            {
                $result[] = phpArray2PostgressSQL($t);
            }
            else
            {
                $t = str_replace('"', '\\"', $t); // escape double quote
                if (! is_numeric($t)) // quote only non-numeric values
                $t = '"' . $t . '"';
                $result[] = $t;
            }
        }
        return '{' . implode(",", $result) . '}'; // format
      }
}