<?php
namespace GDO\Profile;

use GDO\Address\GDT_Phone;
use GDO\Core\GDO_Module;
use GDO\Net\GDT_Url;
use GDO\DB\GDT_Int;
use GDO\UI\GDT_Message;
use GDO\UI\GDT_Link;

final class Module_Profile extends GDO_Module
{
	public $module_priority = 45;
	
	public function getConfig()
	{
		return array(
		);
	}
	
	public function getUserConfig()
	{
		return array(
			GDT_Int::make('profile_views')->unsigned()->initial('0'),
			GDT_Link::make('profile_view')->href(href('Profile', 'View'))->label('link_own_profile'),
		);
	}
	
	public function getUserSettings()
	{
		return array(
			GDT_ICQ::make('profile_icq'),
			GDT_Phone::make('profile_phone'),
			GDT_Phone::make('profile_wapp'),
			GDT_Url::make('profile_website')->reachable()->label('profile_website'),
			GDT_Message::make('profile_about')->label('profile_about'),
		);
	}
}
