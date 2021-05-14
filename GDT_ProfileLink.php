<?php
namespace GDO\Profile;

use GDO\UI\GDT_Link;
use GDO\User\GDO_User;
use GDO\Core\GDT_Template;
use GDO\UI\WithImageSize;
use GDO\Core\GDO;

/**
 * A profile link is a link to a user profile.
 * It can be either an avatar, a displayname or both.
 * 
 * @author gizmore
 * @version 6.10.3
 * @since 6.3.0
 * @see \GDO\Avatar\GDT_Avatar
 * @see GDO_User
 */
final class GDT_ProfileLink extends GDT_Link
{
    use WithImageSize;
    
    ############
    ### User ###
    ############
    /**
     * @return \GDO\User\GDO_User
     */
    public function getUser()
    {
        return $this->forUser;
    }
    
    public function gdo(GDO $gdo=null)
    {
        return parent::gdo($gdo)->forUser($gdo);
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
	    if (module_enabled('Avatar'))
	    {
    		$this->withAvatar = $withAvatar;
	    }
		return $this;
	}

	##############
	### Render ###
	##############
	public function renderCell()
	{
		return GDT_Template::php('Profile', 'cell/profile_link.php', ['field'=>$this]);
	}
	
	public function renderJSON()
	{
	    return $this->getUser()->displayNameLabel();
	}
	
}
