<?php

class EM_Recentreviewproducts_Helper_Data extends Mage_Core_Helper_Abstract
{
	function cutText1($string, $setlength) {
		$length = $setlength;
		if($length<strlen($string)){
			while (($string{$length} != " ") AND ($length > 0)) {
				$length--;
			}
			if ($length == 0) return substr($string, 0, $setlength);
			else return substr($string, 0, $length);
		}else return $string;
	}

	function cuttext2($value, $length)
	{
		if(is_array($value)) list($string, $match_to) = $value;
		else { $string = $value; $match_to = $value{0}; }

		$match_start = stristr($string, $match_to);
		$match_compute = strlen($string) - strlen($match_start);

		if (strlen($string) > $length)
		{
			if ($match_compute < ($length - strlen($match_to)))
			{
				$pre_string = substr($string, 0, $length);
				$pos_end = strrpos($pre_string, " ");
				if($pos_end === false) $string = $pre_string."...";
				else $string = substr($pre_string, 0, $pos_end)."...";
			}
			else if ($match_compute > (strlen($string) - ($length - strlen($match_to))))
			{
				$pre_string = substr($string, (strlen($string) - ($length - strlen($match_to))));
				$pos_start = strpos($pre_string, " ");
				$string = "...".substr($pre_string, $pos_start);
				if($pos_start === false) $string = "...".$pre_string;
				else $string = "...".substr($pre_string, $pos_start);
			}
			else
			{
				$pre_string = substr($string, ($match_compute - round(($length / 3))), $length);
				$pos_start = strpos($pre_string, " "); $pos_end = strrpos($pre_string, " ");
				$string = "...".substr($pre_string, $pos_start, $pos_end)."...";
				if($pos_start === false && $pos_end === false) $string = "...".$pre_string."...";
				else $string = "...".substr($pre_string, $pos_start, $pos_end)."...";
			}

			$match_start = stristr($string, $match_to);
			$match_compute = strlen($string) - strlen($match_start);
		}

		return $string;
	}

}