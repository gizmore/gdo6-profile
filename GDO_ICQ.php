<?php
namespace GDO\Profile;

use GDO\Type\GDO_String;

class GDO_ICQ extends GDO_String
{
	public function __construct()
	{
		$this->min = 5;
		$this->max = 9;
		$this->pattern = "/^[0-9]+$/";
		$this->encoding = self::ASCII;
	}
}
