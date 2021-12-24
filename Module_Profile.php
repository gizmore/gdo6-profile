<?php
namespace GDO\Profile;

use GDO\Core\GDO_Module;
use GDO\DB\GDT_Int;
use GDO\UI\GDT_Link;
use GDO\User\GDO_User;
use GDO\Friends\GDT_ACL;
use GDO\UI\GDT_Title;
use GDO\UI\GDT_Card;
use GDO\UI\GDT_IconButton;

/**
 * Profile module has an API to add settings.
 * It offers profile view and view statistics.
 * 
 * @author gizmore
 * @version 6.10.1
 * @since 6.4.0
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
	
	public function getUserConfig()
	{
		$uid = GDO_User::current()->getID();
		return [
		    GDT_Title::make('user_title'),
			GDT_Int::make('profile_views')->unsigned()->initial('0'),
// 			GDT_IconButton::make('profile_view')->href(href('Profile', 'View', "&id={$uid}"))->icon('face'),
		];
	}
	
	public function getUserSettings()
	{
	    return [
	        GDT_ACL::make('profile_visible')->initial(GDT_ACL::ALL),
// 	        GDT_ACL::make('real_name_visible')->initial(GDT_ACL::FRIENDS),
	    ];
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
		$acl = $this->userSetting($target, 'profile_visible');
		return $acl->hasAccess($user, $target, $reason);
	}
	
	public function canViewSetting(GDO_User $user, GDO_User $target, $settingName)
	{
		/** @var \GDO\Friends\GDT_ACL $acl */
	    $acl = $this->userSetting($target, 'profile_{$settingName}_visible');
		return $acl->hasAccess($user, $target);
	}
	
	public function canSeeRealName(GDO_User $user, GDO_User $target)
	{
	    return true; # Realname in GDO_User is public visible :(
// 	    /** @var \GDO\Friends\GDT_ACL $acl */
// 	    $acl = $this->userSetting($target, 'real_name_visible');
// 	    $reason = '';
// 	    return $acl->hasAccess($user, $target, $reason, false);
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
