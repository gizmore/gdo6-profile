<?php
namespace GDO\Profile\Method;

use GDO\Core\Method;
use GDO\User\GDO_User;
use GDO\User\GDO_UserSetting;
use GDO\Profile\Module_Profile;
use GDO\DB\GDT_UInt;

final class View extends Method
{
	public function gdoParameters()
	{
		return array(
			GDT_UInt::make('id')->initial(GDO_User::current()->getID()),
		);
	}
	
	public function execute()
	{
		return $this->templateProfile($this->gdoParameterVar('id'));
	}
	
	public function templateProfile($userid)
	{
		var_dump($_REQUEST);
		$profileUser = GDO_User::table()->find($userid);
		
		if (!Module_Profile::instance()->canViewProfile(GDO_User::current(), $profileUser))
		{
			return $this->error('err_not_allowed');
		}
		
		return $this->templatePHP('profile.php', ['user' => $profileUser]);
	}
	
	/**
	 * Count number of profile views.
	 * @param GDO_User $profileUser
	 */
	public function onProfileView(GDO_User $profileUser)
	{
		$user = GDO_User::current();
		$userid = $profileUser->getID();
		# Increase views
		if (!$user->tempGet("profileview_$userid"))
		{
			GDO_UserSetting::userInc($profileUser, 'profile_views');
			$user->tempSet("profileview_$userid", 1);
		}
	}

}
