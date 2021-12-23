<?php
namespace GDO\Profile;

use GDO\User\GDO_User;
use GDO\Core\GDT_Template;
use GDO\UI\WithImageSize;
use GDO\Core\GDO;
use GDO\DB\Query;
use GDO\User\GDT_User;

/**
 * A profile link is a link to a user profile.
 * It can be either an avatar, a displayname or both.
 * 
 * @author gizmore
 * @version 6.11.1
 * @since 6.3.0
 * @see \GDO\Avatar\GDT_Avatar
 * @see GDO_User
 */
final class GDT_ProfileLink extends GDT_User
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
    	$me = parent::gdo($gdo);
    	if ($gdo instanceof GDO_User)
    	{
    		$me->forUser($gdo);
    	}
    	else
    	{
    		$user = GDO_User::getById($this->getVar());
    		$me->forUser($user);
    	}
    	return $me;
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
	
	public function renderXML()
	{
	    return sprintf("<%1\$s>%2\$s</%1\$s>\n",
	        $this->name,
	        $this->getUser()->displayNameLabel());
	}
	
	############
	### Join ###
	############
	public function gdoBeforeRead(Query $query)
	{
		$query->joinObject($this->name);
	}
	
}
