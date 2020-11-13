<?php
namespace GDO\Profile;

use GDO\Core\GDO_Module;
use GDO\DB\GDT_Int;
use GDO\UI\GDT_Link;
use GDO\User\GDO_User;
use GDO\Friends\GDT_ACL;
use GDO\Core\GDT;
use GDO\DB\GDT_Checkbox;
use GDO\UI\GDT_Title;
use GDO\UI\GDT_Card;

/**
 * Profile module has an API to add settings.
 * It offers profile view and view statistics.
 * 
 * @author gizmore
 * @version 6.10
 * @since 6.04
 */
final class Module_Profile extends GDO_Module
{
	public $module_priority = 100;
	
	public function getDependencies() { return ['Friends']; }
	
	public function onLoadLanguage() { return $this->loadLanguage('lang/profile'); }
	
	public function href_administrate_module() { return href('Profile', 'GrantUserTitle'); }
	
	public function getClasses()
	{
		return [
			GDO_Profile::class,
		];
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
		    GDT_Title::make('user_title'),
			GDT_Int::make('profile_views')->unsigned()->initial('0'),
			GDT_Link::make('profile_view')->href(href('Profile', 'View', "&id={$uid}"))->icon('face'),
		);
	}
	
	public function getUserSettings()
	{
	    return [
	        GDT_ACL::make('profile_visible')->initial('acl_all'),
	    ];
	}
	
	public function addUserSetting(GDT $gdt, $withACL=false)
	{
	    
	}
	
// 	/**
// 	 * @return GDT[];
// 	 */
// 	public function extUserSettings()
// 	{
// 		$this->extUserSettings = [];
// 		GDT_Hook::callHook('ProfileSettings');
// 		return $this->extUserSettings;
// 	}
// 	public $extUserSettings;
	
// 	public function extUserSettingsWithACLs()
// 	{
// 		$settings = [];
// 		$ext = $this->extUserSettings();
// 		$singleACL = $this->cfgSingleACL();
// 		foreach ($ext as $gdt)
// 		{
// 			$settings[] = $gdt;
// 			if (!$singleACL)
// 			{
// 				$settings[] = GDT_ACL::make("profile_{$gdt->name}_visible")->initial('acl_noone');
// 			}
// 		}
// 		return $settings;
// 	}
	
//     public function getUserSettings()
//     {
//         return array_merge(array(GDT_ACL::make('profile_visible')->initial('acl_all')),
//             $this->extUserSettingsWithACLs());
//     }
    
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
		$acl = $this->userSetting($target, 'profile_visible');
		return $acl->hasAccess($user, $target, $reason);
	}
	
	public function canViewSetting(GDO_User $user, GDO_User $target, $settingName)
	{
		/** @var \GDO\Friends\GDT_ACL $acl */
	    $acl = $this->userSetting($target, 'profile_{$settingName}_visible');
		return $acl->hasAccess($user, $target);
	}
	
	#############
	### Hooks ###
	#############
	public function hookAccountChanged(GDO_User $user)
	{
		
	}
	
	public function hookProfileCard(GDO_User $user, GDT_Card $card)
	{
	    $title = $this->userSettingVar($user, 'user_title');
	    if ($title)
	    {
	        $card->addField(GDT_Title::make('user_title')->var($title));
	    }
	}
		
}
