<?php
use GDO\Profile\Module_Profile;
use GDO\UI\GDT_Back;
use GDO\UI\GDT_Card;
use GDO\Date\Time;
use GDO\Core\GDT_Hook;
use GDO\Friends\GDT_ACL;
use GDO\Avatar\GDT_Avatar;
use GDO\UI\GDT_Label;

/** @var $user \GDO\User\GDO_User **/
$me = $user;
$module = Module_Profile::instance();
$card = GDT_Card::make('profile');

$avatar = module_enabled('Avatar') ? GDT_Avatar::make()->user($user) : null;
$card->avatar($avatar);
$card->title(GDT_Label::make()->label('card_title_profile', [$user->displayNameLabel()]));
$card->subtitle(GDT_Label::make()->label('card_subtitle_profile', [Time::displayAge($user->getRegisterDate())]));

$fields = array(
    $me->gdoColumn('user_name'),
    $me->gdoColumn('user_real_name'),
    $me->gdoColumn('user_level'),
	$me->gdoColumn('user_gender'),
    $me->gdoColumn('user_country')->gdo($me->getCountry()),
    $me->gdoColumn('user_language')->gdo($me->getLanguage()),
);

foreach ($fields as $gdt)
{
	if ($gdt->getVar() !== null)
	{
	    $card->addField($gdt);
	}
}

foreach ($module->getUserSettings() as $gdt)
{
	if ($value = Module_Profile::instance()->userSetting($user, $gdt->name))
	{
		if ($value instanceof GDT_ACL)
		{
			continue;
		}
		if ($value->hasVar())
		{
		    $card->addField($value);
		}
	}
}

$card->actions()->addField(GDT_Back::make());

GDT_Hook::callHook('ProfileCard', $user, $card);

echo $card->gdo($user)->render();

GDT_Hook::callHook('ProfileTemplate', $user);
