<?php
namespace GDO\Profile;
use GDO\UI\GDT_Link;
use GDO\User\GDO_User;
use GDO\Core\GDT_Template;

final class GDT_ProfileLink extends GDT_Link
{
	public $forUser = null;
	public function forUser(GDO_User $user)
	{
		$this->forUser = $user;
		return $this;
	}
	
	public $withNickname;
	public function withNickname($withNickname=true)
	{
		$this->withNickname = $withNickname;
		return $this;
	}
	
	/**
	 * @return \GDO\User\GDO_User
	 */
	public function getUser()
	{
		return $this->forUser;
	}
	
	public function render()
	{
		return GDT_Template::php('Profile', 'cell/profile_link.php', ['field'=>$this]);
	}
	
}
