<?php

/**
  * Validation class, Validate.php
  * Check fields and data validity
  * 
  * @category  classes
  * @author    John Mitros
  * @copyright 2012
  *
  */

class Validate
{
    
 	/**
	* Check for e-mail validity
	*
	* @param string $email e-mail address to validate
	* @return boolean Validity is ok or not
	*/
	public static function isEmail($email)
    {
    	return preg_match('/^[a-z0-9!#$%&\'*+\/=?^`{}|~_-]+[.a-z0-9!#$%&\'*+\/=?^`{}|~_-]*@[a-z0-9]+[._a-z0-9-]*\.[a-z0-9]+$/ui', $email);
    }

	/**
	* Check for MD5 string validity
	*
	* @param string $md5 MD5 string to validate
	* @return boolean Validity is ok or not
	*/
	public static function isMd5($md5)
	{
		return preg_match('/^[a-z0-9]{32}$/ui', $md5);
	}

	/**
	* Check for SHA1 string validity
	*
	* @param string $sha1 SHA1 string to validate
	* @return boolean Validity is ok or not
	*/
	public static function isSha1($sha1)
	{
		return preg_match('/^[a-z0-9]{40}$/ui', $sha1);
	}

	/**
	* Check for a float number validity
	*
	* @param float $float Float number to validate
	* @return boolean Validity is ok or not
	*/
    public static function isFloat($float)
    {
		return strval(floatval($float)) == strval($float);
	}
	
    public static function isUnsignedFloat($float)
    {
			return strval(floatval($float)) == strval($float) && $float >= 0;
	}

	/**
	* Check for an image size validity
	*
	* @param string $size Image size to validate
	* @return boolean Validity is ok or not
	*/
	public static function isImageSize($size)
	{
		return preg_match('/^[0-9]{1,4}$/ui', $size);
	}

	/**
	* Check for name validity
	*
	* @param string $name Name to validate
	* @return boolean Validity is ok or not
	*/
	public static function isName($name)
	{
		return preg_match('/^[^0-9!<>,;?=+()@#"�{}_$%:]*$/ui', stripslashes($name));
	}

	/**
	* Check for sender name validity
	*
	* @param string $mailName Sender name to validate
	* @return boolean Validity is ok or not
	*/
	public static function isSenderName($mailName)
	{
		return preg_match('/^[^<>;=#{}]*$/ui', $mailName);
	}

	/**
	* Check for e-mail subject validity
	*
	* @param string $mailSubject e-mail subject to validate
	* @return boolean Validity is ok or not
	*/
	public static function isMailSubject($mailSubject)
	{
		return preg_match('/^[^<>;{}]*$/ui', $mailSubject);
	}

	/**
	* Check for icon file validity
	*
	* @param string $icon Icon filename to validate
	* @return boolean Validity is ok or not
	*/
	public static function isIconFile($icon)
	{
		return preg_match('/^[a-z0-9_-]+\.[gif|jpg|jpeg|png]$/ui', $icon);
	}

	/**
	* Check for ico file validity
	*
	* @param string $icon Icon filename to validate
	* @return boolean Validity is ok or not
	*/
	public static function isIcoFile($icon)
	{
		return preg_match('/^[a-z0-9_-]+\.ico$/ui', $icon);
	}

	/**
	* Check for image type name validity
	*
	* @param string $type Image type name to validate
	* @return boolean Validity is ok or not
	*/
	public static function isImageTypeName($type)
	{
		return preg_match('/^[a-z0-9_ -]+$/ui', $type);
	}

	/**
	* Check for a message validity
	*
	* @param string $message Message to validate
	* @return boolean Validity is ok or not
	*/
	public static function isMessage($message)
	{
		return preg_match('/^([^<>{}]|<br \/>)*$/ui', $message);
	}
    
    /**
	* Check for a varchar name validity
	*
	* @param string $name Field name to validate
	* @return boolean Validity is ok or not
	*/
	public static function isVarchar($varchar)
	{
		return preg_match('/^[\p{L}\s0-9]{0,50}$/ui', $varchar);
	}

	/**
	* Check for a link (url-rewriting only) validity
	*
	* @param string $link Link to validate
	* @return boolean Validity is ok or not
	*/
	public static function isLinkRewrite($link)
	{
		return empty($link) or preg_match('/^[_a-z0-9-]+$/ui', $link);
	}

	/**
	* Check for search query validity
	*
	* @param string $search Query to validate
	* @return boolean Validity is ok or not
	*/
	public static function isValidSearch($search)
	{
		return preg_match('/^[^<>;=#{}]{0,64}$/ui', $search);
	}

	/**
	* Check for standard name validity
	*
	* @param string $name Name to validate
	* @return boolean Validity is ok or not
	*/
	public static function isGenericName($name)
	{
		return empty($name) or preg_match('/^[^<>;=#{}]*$/ui', $name);
	}

	/**
	* Check for HTML field validity (no XSS please !)
	*
	* @param string $html HTML field to validate
	* @return boolean Validity is ok or not
	*/
	public static function isCleanHtml($html)
	{
		$jsEvent = 'onmousedown|onmousemove|onmmouseup|onmouseover|onmouseout|onload|onunload|onfocus|onblur|onchange|onsubmit|ondblclick|onclick|onkeydown|onkeyup|onkeypress|onmouseenter|onmouseleave';
		return (!preg_match('/<[ \t\n]*script/ui', $html) && !preg_match('/<.*('.$jsEvent.')[ \t\n]*=/ui', $html)  && !preg_match('/.*script\:/ui', $html));
	}

	/**
	* Check for password validity
	*
	* @param string $passwd Password to validate
	* @return boolean Validity is ok or not
	*/
	public static function isPasswd($passwd, $size = 5)
	{
		return preg_match('/^[.a-z_0-9-!@#$%\^&*()]{'.$size.',40}$/ui', $passwd);
	}

	/**
	* Check for date validity
	*
	* @param string $date Date to validate
	* @return boolean Validity is ok or not
	*/
	public static function isDate($date)
	{
		if (!preg_match('/^([0-9]{4})-((0?[1-9])|(1[0-2]))-((0?[1-9])|([1-2][0-9])|(3[01]))( [0-9]{2}:[0-9]{2}:[0-9]{2})?$/ui', $date, $matches))
			return false;
		return checkdate(intval($matches[2]), intval($matches[5]), intval($matches[0]));
	}

	/**
	* Check for boolean validity
	*
	* @param boolean $bool Boolean to validate
	* @return boolean Validity is ok or not
	*/
	public static function isBool($bool)
	{
		return is_null($bool) || is_bool($bool) || preg_match('/^[0|1]{1}$/ui', $bool);
	}

	/**
	* Check for an integer validity
	*
	* @param integer $id Integer to validate
	* @return boolean Validity is ok or not
	*/
	public static function isInt($value)
	{
		return ((string)(int)$value === (string)$value or $value === false);
	}

	/**
	* Check for an integer validity (unsigned)
	*
	* @param integer $id Integer to validate
	* @return boolean Validity is ok or not
	*/
	public static function isUnsignedInt($value)
	{
		return (self::isInt($value) && $value < 4294967296 && $value >= 0);
	}

	/**
	* Check for an integer validity (unsigned)
	* Mostly used in database for auto-increment
	*
	* @param integer $id Integer to validate
	* @return boolean Validity is ok or not
	*/
	public static function isUnsignedId($id)
	{
		return self::isUnsignedInt($id); /* Because an id could be equal to zero when there is no association */
	}

	public static function isNullorUnsignedId($id)
	{
		return is_null($id) or self::isUnsignedId($id);
	}

	/**
	* Check url validity
	*
	* @param string $url to validate
	* @return boolean Validity is ok or not
	*/
	public static function isUrl($url)
	{
		return preg_match('/^([[:alnum:]]|[:#%&_=\(\)\.\? \+\-@\/])+$/ui', $url);
	}

	/**
	* Check absolute url validity
	*
	* @param string $url to validate
	* @return boolean Validity is ok or not
	*/
	public static function isAbsoluteUrl($url)
	{
		if (!empty($url))
			return preg_match('/^https?:\/\/([[:alnum:]]|[:#%&_=\(\)\.\? \+\-@\/])+$/ui', $url);
		return true;
	}

	/**
	* Check for standard name file validity
	*
	* @param string $name Name to validate
	* @return boolean Validity is ok or not
	*/
	public static function isFileName($name)
	{
		return preg_match('/^[a-z0-9_.-]*$/ui', $name);
	}

	public static function isProtocol($protocol)
	{
		return preg_match('/^http(s?):\/\/$/ui', $protocol);
	}
}