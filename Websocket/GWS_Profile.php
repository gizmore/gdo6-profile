<?php
namespace GDO\Profile\Websocket;

use GDO\Websocket\Server\GWS_Command;
use GDO\Websocket\Server\GWS_Message;
use GDO\Websocket\Server\GWS_Commands;
use GDO\User\GDO_User;
use GDO\Profile\GDO_Profile;
use GDO\Friends\GDT_ACL;
use GDO\Profile\Method\View;
use GDO\Friends\GDO_Friendship;
use GDO\Profile\Module_Profile;
use GDO\Core\ModuleLoader;

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
		$user = GDO_User::findById($msg->read32u()); # target user

		/** @var $globalACL GDT_ACL **/
		$globalACL = Module_Profile::instance()->userSetting($user, 'profile_visible');
		$reason = '';
		$globalACL->hasAccess($me, $user, $reason);

	    View::make()->onProfileView($user);
		
		$profile = GDO_Profile::blank(); # The profile GDO/DTO
		$profile->setVar($globalACL->name, $globalACL->getVar());
		
		foreach (ModuleLoader::instance()->getEnabledModules() as $module)
		{
		    $settings = $module->getSettingsCache();
		    foreach ($settings as $gdt)
		    {
		        $profile->setVar($gdt->name, null);
		        $aclName = $gdt->name . '_visible';
		        if ($module->hasSetting($aclName))
		        {
    		        /** @var $fieldACL GDT_ACL **/
    		        $fieldACL = $module->userSetting($user, $aclName);
    		        if ($fieldACL->hasAccess($me, $user, $reason, false))
    		        {
    		            $profile->setVar($gdt->name, $gdt->var);
    		        }
		        }
		        else
		        {
		            $profile->setVar($gdt->name, $gdt->var);
		        }
		    }
		}
		
		$payload = $msg->wr32($user->getID());
		$payload .= $msg->wr8(GDO_Friendship::areRelated($me, $user)?1:0);
		$payload .= $this->gdoToBinary($profile);
		return $msg->replyBinary($msg->cmd(), $payload);
	}

}

# Register command
GWS_Commands::register(0x0901, new GWS_Profile());
