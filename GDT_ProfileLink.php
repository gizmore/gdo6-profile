<?php
namespace GDO\Profile;

use GDO\UI\GDT_Link;
use GDO\User\GDO_User;
use GDO\Core\GDT_Template;
use GDO\UI\WithImageSize;

/**
 * A profile link is a link to a user profile.
 * It can be either an avatar, a displayname or both.
 * 
 * @author gizmore
 * @version 6.10
 * @since 6.03
 * @see \GDO\Avatar\GDT_Avatar
 * @see GDO_User
 */
final class GDT_ProfileLink extends GDT_Link
{
    use WithImageSize;
    
    ############
    ### User ###
    ############^
    /**
     * @return \GDO\User\GDO_User
     */
    public function getUser()
    {
        return $this->forUser;
    }
    
    public $forUser = null;
	public function forUser(GDO_User $user)
	{
		$this->forUser = $user;
		return $this;
	}
	
	############
	### Nick ###
	############
	public $withNickname = false;
	public function withNickname($withNickname=true)
	{
		$this->withNickname = $withNickname;
		return $this;
	}
	
	##############
	### Avatar ###
	##############
	public $withAvatar = false;
	public function withAvatar($withAvatar=true)
	{
		$this->withAvatar = $withAvatar;
		return $this;
	}

	##############
	### Render ###
	##############
	public function renderCell()
	{
		return GDT_Template::php('Profile', 'cell/profile_link.php', ['field'=>$this]);
	}
	
}
