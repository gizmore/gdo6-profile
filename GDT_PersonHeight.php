<?php
namespace GDO\Profile;

use GDO\DB\GDT_Float;

final class GDT_PersonHeight extends GDT_Float
{
	public $min = 0.50;
	public $max = 2.60;
	public $step = 0.01;
	
	public function defaultLabel() { return $this->label("person_height"); }
}
