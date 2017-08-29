<?php
use GDO\Profile\Module_Profile;
use GDO\UI\GDT_Back;
use GDO\User\User;
use GDO\User\UserSetting;

$user instanceof User;
// $profile instanceof GWF_Profile;
$module = Module_Profile::instance();
?>
<md-card>
  <md-card-title>
    <md-card-title-text>
      <span class="md-headline"><?= t('card_title_profile', [$user->displayName()]); ?></span>
      <span class="md-subhead"><?= t('card_subtitle_profile', [tt($user->getRegisterDate())]); ?></span>
    </md-card-title-text>
  </md-card-title>
  <md-card-content layout="column" layout-align="space-between">
    <?php foreach ($module->getUserSettings() as $gdoType) : ?>
    <div>
      <label><?= $gdoType->label; ?></label>
      <i><?= UserSetting::userGet($user, $gdoType->name)->getValue(); ?></i>
    </div>
    <?php endforeach; ?>
  </md-card-content>
  <md-card-actions layout="row" layout-align="end center">
    <?= GDT_Back::make()->renderCell(); ?>
  </md-card-actions>
</md-card>
