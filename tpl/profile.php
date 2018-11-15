<?php
use GDO\Profile\Module_Profile;
use GDO\UI\GDT_Back;
use GDO\User\GDO_User;
use GDO\User\GDO_UserSetting;
use GDO\UI\GDT_Card;
use GDO\UI\GDT_HTML;
use GDO\Date\Time;
use GDO\Avatar\GDO_Avatar;
use GDO\Core\GDT_Hook;
$me = $user;
$me instanceof GDO_User;
$module = Module_Profile::instance();
$card = GDT_Card::make('profile');

$avatar = GDO_Avatar::renderAvatar($user);
$username = t('card_title_profile', [$user->displayNameLabel()]);
$since = t('card_subtitle_profile', [Time::displayAge($user->getRegisterDate())]);
$title = <<<EOT
{$avatar}
<div class="ib">{$username}<br/>{$since}</div>
EOT;
$card->title($title);

$content = '';
foreach ($module->getUserSettings() as $gdt)
{
	if ($value = GDO_UserSetting::userGet($user, $gdt->name)->getValue())
	{
		$content .= '<div class="profile-row">';
		$content .= sprintf('<label>%s</label>', $gdt->displayLabel());
		$content .= sprintf('<span>%s</span>', $value);
		$content .= '</div>';
	}
}
$card->addField(GDT_HTML::withHTML($content));

$card->actions()->addField(GDT_Back::make());

GDT_Hook::call('ProfileCard', $user, $card);

echo $card->render();
