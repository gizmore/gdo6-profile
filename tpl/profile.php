<?php
use GDO\Profile\Module_Profile;
use GDO\UI\GDT_Back;
use GDO\User\GDO_User;
use GDO\User\GDO_UserSetting;
use GDO\UI\GDT_Card;
use GDO\UI\GDT_HTML;
use GDO\UI\WithHTML;
use GDO\Date\Time;
$user instanceof GDO_User;
$module = Module_Profile::instance();
$card = GDT_Card::make('profile');

$title = '';
$title .= t('card_title_profile', [$user->displayNameLabel()]);
$title .= t('card_subtitle_profile', [Time::displayAge($user->getRegisterDate())]);
$card->title($title);

$content = '';
foreach ($module->getUserSettings() as $gdt)
{
	if ($value = GDO_UserSetting::userGet($user, $gdt->name)->getValue())
	{
		$content .= '<div>';
		$content .= sprintf('<label>%s</label>', $gdt->label);
		$content .= sprintf('<i>%s</i>', $value);
		$content .= '</div>';
	}
}
$card->addField(GDT_HTML::withHTML($content));

$card->actions()->addField(GDT_Back::make());

echo $card->render();
