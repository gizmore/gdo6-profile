<?php /** @var $field \GDO\Profile\GDT_ProfileLink **/
use GDO\Avatar\GDO_Avatar;
$user = $field->getUser();
$hrefProfile = href('Profile', 'View', "&id={$user->getID()}");
?>
<a class="gdo-profile-link" title="<?=$user->displayNameLabel();?>" href="<?=$hrefProfile?>">
<?php if ($field->withAvatar && module_enabled('Avatar')) : ?>
  <?=GDO_Avatar::renderAvatar($user, $field->imageWidth)?>
<?php endif; ?>
<?php if ($field->withNickname) : ?>
  <span>
    <?=$user->displayNameLabel()?>
  </span>
<?php endif; ?>
</a>
