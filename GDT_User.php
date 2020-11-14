<?php
namespace GDO\Profile;

use GDO\UI\GDT_Link;

/**
 * Shows a profile link instead of username.
 * 
 * @author gizmore
 * @since 6.09
 */
final class GDT_User extends \GDO\User\GDT_User
{
	public function getLink($name=null)
	{
		if ($user = $this->getUser())
		{
			return GDT_Link::make($name?$name:$this->name)->
				labelRaw($user->displayNameLabel())->
				href(href('Profile', 'View', "&id={$user->getID()}"));
		}
	}

	public function renderCell() { return $this->getLink()->renderCell(); }
}
