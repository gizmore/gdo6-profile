<?php
namespace GDO\Profile;

use GDO\Core\GDO;

/**
 * Abuses the GDO class to be a Websocket-DTO.
 * This table is simply never installed.
 * 
 * @see GWS_Profile
 * @see Module_Profile
 * @author gizmore@wechall.net
 */
final class GDO_Profile extends GDO
{
	public function gdoAbstract() { return false; }
	public function gdoIsTable() { return false; }
	public function gdoCached() { return false; }
	public function gdoColumns()
	{
		# Simply return User settings for profile.
		return Module_Profile::instance()->getUserSettings();
	}
}
