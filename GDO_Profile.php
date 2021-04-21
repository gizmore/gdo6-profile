<?php
namespace GDO\Profile;

use GDO\Core\GDO;
use GDO\Core\ModuleLoader;

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
	    $columns = [];
	    foreach (ModuleLoader::instance()->getInstallableModules() as $module)
	    {
	        foreach ($module->getSettingsCache() as $gdt)
	        {
	            if ($gdt->isSerializable())
	            {
	                $columns[] = $gdt;
	            }
	        }
	    }
	    return $columns;
	}
}
