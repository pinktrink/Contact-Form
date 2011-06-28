<?php
class Validation{
	public static $regex = array(
		'name' => '/[A-Z- ]+/i',
		'letters' => '/[A-Z]+/i',
		'email' => '/^[\w\d%+-.]+(?<![._%+-])@(?:[A-Z\d-]+\.)+[A-Z]{2,4}$/i',
		'phone-us' => '/\d{3}[-. ]\d{3}[-. ](?:\d{4}|\d{2}[-. ]\d{2})(?: ?(?:(?:x|ex|ext|extension) ?\d{1-5}))?/i',
		'phone-us' => '/\d{3}[-. ]\d{3}[-. ](?:\d{4}|\d{2}[-. ]\d{2})'
	);
	
	public static $maps = array(
		'>' => 'gt',
		'>=' => 'gte',
		'<' => 'lt',
		'<=' => 'lte',
		'=' => 'equal',
		'!' => 'notequal',
		'required' => 'filled',
		'empty' => 'blank'
	);
	
	public static $errors = array(
		'numeric' => 'This field must be numeric.',
		'filled' => 'This field is required.',
		'blank' => 'This field must be blank.',
		'min' => 'This field must be a no less than %s characters long.',
		'max' => 'This field must be a no more than %s characters long.',
		'size' => 'This field must be exactly %s characters long.',
		'equal' => 'This field\'s value must be %s.',
		'notequal' => 'This field\'s value cannot be %s.'
	);
	
	public static $lasterror = '';
	
	public static function trim(&$data){
		$data = trim($data);
		return true;
	}
	
	public static function rtrim(&$data){
		$data = rtrim($data);
		return true;
	}
	
	public static function ltrim(&$data){
		$data = ltrim($data);
		return true;
	}
	
	public static function numeric($data){
		if(is_numeric($data)) return true;
		self::$lasterror = sprintf(self::$errors['numeric']);
		return false;
	}
	
	public static function filled($data){
		if(!empty($data)) return true;
		self::$lasterror = sprintf(self::$errors['filled']);
		return false;
	}
	
	public static function blank($data){
		if(empty($data)) return true;
		self::$lasterror = sprintf(self::$errors['blank']);
		return false;
	}
	
	public static function optional($data){
		return true;
	}
	
	public static function min($data, $extra){
		if(strlen($data) >= (int)$extra) return true;
		self::$lasterror = sprintf(self::$errors['min'], $extra);
		return false;
	}
	
	public static function max($data, $extra){
		if(strlen($data) <= (int)$extra) return true;
		self::$lasterror = sprintf(self::$errors['max'], $extra);
		return false;
	}
	
	public static function size($data, $extra){
		if(is_scalar($extra)){
			if(strlen($data) === (int)$extra) return true;
			self::$lasterror = sprintf(self::$errors['size'], $extra);
		}else{
			if(in_array((string)strlen($data), $extra)) return true;
			$last = array_pop($extra);
			self::$lasterror = sprintf(self::$errors['size'], implode(', ', $extra) . ", or $last");
		}
		return false;
	}
	
	public static function truncate(&$data, $extra){
		if(strlen($data) > (int)$extra) $data = substr($data, 0, (int)$extra);
		return true;
	}
	
	public static function equal($data, $extra){
		if(is_scalar($extra)){
			if($data === $extra) return true;
			self::$lasterror = sprintf(self::$errors['equal'], $extra);
		}else{
			if(in_array($data, $extra)) return true;
			$last = array_pop($extra);
			self::$lasterror = sprintf(self::$errors['equal'], implode(', ', $extra) . ", or $last");
		}
		return false;
	}
	
	public static function notequal($data, $extra){
		if(is_scalar($extra)){
			if($data !== $extra) return true;
			self::$lasterror = sprintf(self::$errors['notequal'], $extra);
		}else{
			if(!in_array($data, $extra)) return true;
			$last = array_pop($extra);
			self::$lasterror = sprintf(self::$errors['notequal'], implode(', ', $extra) . ", or $last");
		}
		return false;
	}
	
	public static function gt($data, $extra){
		if($data > $extra) return true;
		return false;
	}
	
	public static function gte($data, $extra){
		if($data >= $extra) return true;
		return false;
	}
	
	public static function lt($data, $extra){
		if($data < $extra) return true;
		return false;
	}
	
	public static function lte($data, $extra){
		if($data <= $extra) return true;
		return false;
	}
	
	public static function regex($data, $extra){
		if(preg_match(self::$regex[$extra], $data, $null)) return true;
		return false;
	}
	
	public static function notregex($data, $extra){
		if(!preg_match(self::$regex[$extra], $data, $null)) return true;
		return false;
	}
}
?>