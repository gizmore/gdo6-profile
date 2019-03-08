<?php
namespace GDO\Profile;

use GDO\Core\GDO_Module;
use GDO\DB\GDT_Int;
use GDO\UI\GDT_Link;
use GDO\User\GDO_User;
use GDO\User\GDO_UserSetting;
use GDO\Core\GDT_Hook;
use GDO\Friends\GDT_ACL;
use GDO\Core\GDT;
use GDO\DB\GDT_Checkbox;

final class Module_Profile extends GDO_Module
{
	public $module_priority = 100;
	
	public function onLoadLanguage() { return $this->loadLanguage('lang/profile'); }
	
	public function getClasses()
	{
		return array(
			'GDO\Profile\GDO_Profile',
		);
	}
	
	public function getConfig()
	{
		return array(
			GDT_Checkbox::make('profile_single_acl')->initial('1'),
		);
	}
	public function cfgSingleACL() { return $this->getConfigValue('profile_single_acl'); }
	
	public function getUserConfig()
	{
		$uid = GDO_User::current()->getID();
		return array(
			GDT_Int::make('profile_views')->unsigned()->initial('0'),
			GDT_Link::make('profile_view')->href(href('Profile', 'View', "&id={$uid}"))->label('link_own_profile'),
		);
	}
	
	/**
	 * @return GDT[];
	 */
	public function extUserSettings()
	{
		$this->extUserSettings = [];
		GDT_Hook::callHook('ProfileSettings');
		return $this->extUserSettings;
	}
	public $extUserSettings;
	
	public function extUserSettingsWithACLs()
	{
		$settings = [];
		$ext = $this->extUserSettings();
		$singleACL = $this->cfgSingleACL();
		foreach ($ext as $gdt)
		{
			$settings[] = $gdt;
			if (!$singleACL)
			{
				$settings[] = GDT_ACL::make("profile_{$gdt->name}_visible")->initial('acl_noone');
			}
		}
		return $settings;
	}
	
	public function getUserSettings()
	{
		return array_merge(array(GDT_ACL::make('profile_visible')->initial('acl_all')),
			$this->extUserSettingsWithACLs());
	}
	
	public function onIncludeScripts()
	{
		$this->addCSS('css/profile.css');
	}
	
	##############
	### Helper ###
	##############
	public function canViewProfile(GDO_User $user, GDO_User $target, &$reason)
	{
		/**
		 * @var \GDO\Friends\GDT_ACL $acl
		 */
		$acl = GDO_UserSetting::userGet($target, 'profile_visible');
		return $acl->hasAccess($user, $target, $reason);
	}
	
	public function canViewSetting(GDO_User $user, GDO_User $target, $settingName)
	{
		/** @var \GDO\Friends\GDT_ACL $acl */
		$acl = GDO_UserSetting::userGet($target, "profile_{$settingName}_visible");
		return $acl->hasAccess($user, $target);
	}
	
	#############
	### Hooks ###
	#############
	public function hookAccountChanged(GDO_User $user)
	{
		
	}
		
}
