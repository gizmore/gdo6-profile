<?php
namespace GDO\Profile\Method;

use GDO\Core\Method;
use GDO\User\User;
use GDO\User\UserSetting;
use GDO\Util\Common;

final class View extends Method
{
	public function execute()
	{
		return $this->templateProfile(Common::getRequestString('id', User::current()->getID()));
	}
	
	public function templateProfile(string $userid)
	{
		$profileUser = User::table()->find($userid);
		return $this->templatePHP('profile.php', ['user' => $profileUser]);
	}
	
	public function onProfileView(User $profileUser)
	{
		$user = User::current();
		$userid = $profileUser->getID();
		# Increase views
		if (!$user->tempGet("profileview_$userid"))
		{
			$views = UserSetting::userInc($profileUser, 'profile_views');
			$user->tempSet("profileview_$userid", 1);
		}
		
	}
}
