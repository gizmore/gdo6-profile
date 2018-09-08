<?php
namespace GDO\Profile;

use GDO\Address\GDT_Phone;
use GDO\Core\GDO_Module;
use GDO\Net\GDT_Url;
use GDO\DB\GDT_Enum;
use GDO\DB\GDT_Int;
use GDO\UI\GDT_Message;
use GDO\UI\GDT_Link;
use GDO\DB\GDT_String;
use GDO\User\GDO_User;
use GDO\User\GDO_UserSetting;
use GDO\Friends\GDO_Friendship;
use GDO\Core\GDT_Hook;

final class Module_Profile extends GDO_Module
{
	public $module_priority = 45;
	
	public function onLoadLanguage() { return $this->loadLanguage('lang/profile'); }
	
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
			GDT_Enum::make('profile_visible')->enumValues('all', 'members', 'friends', 'none')->initial('all')->notNull(),
			GDT_ICQ::make('profile_icq'),
			GDT_String::make('profile_skype')->label('skype_name'),
			GDT_Phone::make('profile_phone'),
			GDT_Phone::make('profile_wapp'),
			GDT_Url::make('profile_website')->reachable()->label('profile_website'),
			GDT_Message::make('profile_about')->label('profile_about'),
// 			GDT_EyeColor::make('profile_eyes')->label('eye_color'),
// 			GDT_SexualOrientation::make('profile_sex_pref'),
		);
	}
	
	public function onIncludeScripts()
	{
		$this->addCSS('css/profile.css');
	}
	
	##############
	### Helper ###
	##############
	public function canViewProfile(GDO_User $user, GDO_User $target)
	{
		# Self is fine
		if ($user === $target)
		{
			return true;
		}
		
		# Check setting
		$visibility = GDO_UserSetting::userGet($target, 'profile_visible')->getVar();
		switch ($visibility)
		{
		case 'all': return true;
		case 'members': return $user->isMember();
		case 'friends': return module_enabled('Friends') ? GDO_Friendship::areRelated($user, $target) : false;
		case 'none': return false;
		}
		return false;
	}
		
}
