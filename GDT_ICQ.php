<?php
namespace GDO\Profile;

use GDO\DB\GDT_String;

class GDT_ICQ extends GDT_String
{
	public $pattern = "/^[0-9]+$/";
	public $encoding = self::ASCII;
	public $caseSensitive = true;
	public $min = 5;
	public $max = 9;
}
