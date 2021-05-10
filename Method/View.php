<?php
namespace GDO\Profile\Method;

use GDO\Core\Method;
use GDO\User\GDO_User;
use GDO\Profile\Module_Profile;
use GDO\DB\GDT_UInt;
use GDO\User\GDT_Username;

final class View extends Method
{
	public function isUserRequired() { return true; }
	
	public function gdoParameters()
	{
		return array(
			GDT_UInt::make('id')->initial(GDO_User::current()->getID()),
		    GDT_Username::make('user'),
		);
	}
	
	public function execute()
	{
		return $this->templateProfile($this->gdoParameterVar('id'));
	}
	
	public function getTitle()
	{
	    return t('card_title_profile', [$this->user->displayNameLabel()]);
	}
	
	public $user;
	public function init()
	{
	    if ($username = $this->gdoParameterVar('user'))
	    {
	        $this->user = GDO_User::getByName($username);
	    }
	    if ($userid = $this->gdoParameterVar('id'))
	    {
	        $this->user = GDO_User::findById($userid);
	    }
	}
	
	public function templateProfile($userid)
	{
		$profileUser = $this->user;
		
		$reason = '';
		if (!Module_Profile::instance()->canViewProfile(GDO_User::current(), $profileUser, $reason))
		{
			return $this->error('err_not_allowed', [$reason]);
		}
		
		if ($profileUser !== GDO_User::current())
		{
		    $this->onProfileView($profileUser);
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
		    Module_Profile::instance()->increaseUserSetting($profileUser, 'profile_views');
			$user->tempSet("profileview_$userid", 1);
			$user->recache();
		}
	}

}
