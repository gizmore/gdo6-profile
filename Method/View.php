<?php
namespace GDO\Profile\Method;

use GDO\Core\Method;
use GDO\User\GDO_User;
use GDO\User\GDO_UserSetting;
use GDO\Util\Common;

final class View extends Method
{
	public function execute()
	{
		return $this->templateProfile(Common::getRequestString('id', GDO_User::current()->getID()));
	}
	
	public function templateProfile($userid)
	{
		$profileGDO_User = GDO_User::table()->find($userid);
		return $this->templatePHP('profile.php', ['user' => $profileUser]);
	}
	
	public function onProfileView(GDO_User $profileUser)
	{
		$user = GDO_User::current();
		$userid = $profileUser->getID();
		# Increase views
		if (!$user->tempGet("profileview_$userid"))
		{
			$views = GDO_UserSetting::userInc($profileUser, 'profile_views');
			$user->tempSet("profileview_$userid", 1);
		}
		
	}
}
