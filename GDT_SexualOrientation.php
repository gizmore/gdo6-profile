<?php
namespace GDO\Profile;

use GDO\DB\GDT_Enum;

class GDT_SexualOrientation extends GDT_Enum
{
	public function __construct()
	{
		$this->label('sexual_orientation');
		$this->enumValues('men', 'women', 'both', 'none');
		$this->emptyLabel(t('not_specified'));
	}
}
