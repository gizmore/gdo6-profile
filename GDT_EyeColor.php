<?php
namespace GDO\Profile;

use GDO\DB\GDT_Enum;

final class GDT_EyeColor extends GDT_Enum
{
	public static $COLORS = [
		'amber',
		'green',
		'green_brown',
		'gray',
		'blue',
		'light_brown',
		'light_blue',
		'blue_green',
	];
	
	public function __construct()
	{
		$this->label('eye_color');
		$this->enumValues(...self::$COLORS);
		$this->emptyLabel('not_specified');
	}
}
