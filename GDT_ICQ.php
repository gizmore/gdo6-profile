<?php
namespace GDO\Profile;

use GDO\Type\GDT_String;

class GDT_ICQ extends GDT_String
{
	public function __construct()
	{
		$this->min = 5;
		$this->max = 9;
		$this->pattern = "/^[0-9]+$/";
		$this->encoding = self::ASCII;
	}
}
