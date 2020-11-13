<?php
namespace GDO\Profile\Websocket;

use GDO\Websocket\Server\GWS_Command;
use GDO\Websocket\Server\GWS_Message;
use GDO\Websocket\Server\GWS_Commands;
use GDO\User\GDO_User;
use GDO\Profile\GDO_Profile;
use GDO\Friends\GDT_ACL;
use GDO\Core\GDT;
use GDO\Profile\Method\View;
use GDO\Friends\GDO_Friendship;
use GDO\Profile\Module_Profile;

/**
 * Profile view.
 * Respects ACL for each item.
 * @author gizmore@wechall.net
 */
final class GWS_Profile extends GWS_Command
{
	public function execute(GWS_Message $msg)
	{
		$me = $msg->user(); # own user
		$user = GDO_User::getById($msg->read32u()); # target user

		$settings = array_values(GDO_Profile::table()->gdoColumnsCache()); # The settings
		$profile = GDO_Profile::blank(); # The profile GDO/DTO
		
		# Iterate over all settings
		$i = 0;

		# First is overall acl setting
		$global = $this->getSettingACL($user, $settings, $i++);
		$reason = '';
		$globalAccess = $global->hasAccess($me, $user, $reason);
		$profile->setVar($global->name, $global->getVar());
		
		$singleACL = Module_Profile::instance()->cfgSingleACL();
		
		# Now come pairs (two gdt per setting) of "gdt,gdt_acl"
		while ($i < count($settings))
		{
			if (!$singleACL)
			{
				# ACL setting
				$acl = $this->getSettingACL($user, $settings, $i+1);
				$profile->setVar($acl->name, $acl->getVar());
			}
			
			# setting value
			$gdt = $this->getSettingGDT($user, $settings, $i);
			$var = $gdt->initial;
			if ($globalAccess)
			{
				if  ($singleACL || $acl->hasAccess($me, $user, $reason))
				{
					$var = $gdt->var;
				}
			}

			$profile->setVar($settings[$i]->name, $var);
			
			# Next pair
			$i += $singleACL ? 1 : 2;
		}
		
		if ($globalAccess)
		{
			View::make()->onProfileView($user);
		}
		
		$payload = $msg->wr32($user->getID());
		$payload .= $msg->wr8(GDO_Friendship::areRelated($me, $user)?1:0);
		$payload .= $this->gdoToBinary($profile);
		return $msg->replyBinary($msg->cmd(), $payload);
	}

	###############
	### Private ###
	###############
	/**
	 * @param array $settings
	 * @return GDT_ACL
	 */
	private function getSettingACL(GDO_User $user, array $settings, $i)
	{
		return $this->getSettingGDT($user, $settings, $i);
	}
	
	/**
	 * @param array $settings
	 * @return GDT
	 */
	private function getSettingGDT(GDO_User $user, array $settings, $i)
	{
		return  Module_Profile::instance()->userSetting($user, $settings[$i]->name);
	}
	
}

# Register command
GWS_Commands::register(0x0901, new GWS_Profile());
