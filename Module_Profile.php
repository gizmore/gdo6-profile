<?php
namespace GDO\Profile;

use GDO\Address\GDO_Phone;
use GDO\Core\Module;
use GDO\Net\GDO_Url;
use GDO\Type\GDO_Int;
use GDO\Type\GDO_Message;
use GDO\UI\GDO_Link;

final class Module_Profile extends Module
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
			GDO_Int::make('profile_views')->unsigned()->initial('0'),
			GDO_Link::make('profile_view')->href(href('Profile', 'View'))->label('link_own_profile'),
		);
	}
	
	public function getUserSettings()
	{
		return array(
			GDO_ICQ::make('profile_icq'),
			GDO_Phone::make('profile_phone'),
			GDO_Phone::make('profile_wapp'),
			GDO_Url::make('profile_website')->reachable()->label('profile_website'),
			GDO_Message::make('profile_about')->label('profile_about'),
		);
	}
	
}
