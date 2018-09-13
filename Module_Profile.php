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
use GDO\Friends\GDT_ACL;
use GDO\Form\GDT_Select;

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
	
	public $extUserSettings;
	public function getUserSettings()
	{
		$this->extUserSettings = [];
		# Fill via Modules
		GDT_Hook::call('ProfileSettings');
		
		$settings = $this->extUserSettings;
		$fields = [];
		foreach ($settings as $gdt)
		{
			$fields[] = $gdt->name;
		}
		
		array_unshift($settings, GDT_Select::make('profile_visible_none')->choices($fields)->multiple());
		array_unshift($settings, GDT_Select::make('profile_visible_friends')->choices($fields)->multiple());
		array_unshift($settings, GDT_Select::make('profile_visible_member')->choices($fields)->multiple());
		array_unshift($settings, GDT_Select::make('profile_visible_all')->choices($fields)->multiple());
		array_unshift($settings, GDT_ACL::make('profile_visible')->initial('acl_all'));
		
		return $settings;
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
	
	#############
	### Hooks ###
	#############
	public function hookAccountChanged(GDO_User $user)
	{
		
	}
		
}
