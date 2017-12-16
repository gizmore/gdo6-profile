<?php /** @var $field \GDO\Profile\GDT_ProfileLink **/
use GDO\Avatar\GDO_Avatar;
$user = $field->getUser();
$hrefProfile = href('Profile', 'View', "&id={$user->getID()}");
?>
<a title="<?=$user->displayNameLabel();?>" href="<?=$hrefProfile?>">
  <?=GDO_Avatar::renderAvatar($user)?>
<?php if ($field->withNickname) : ?>
  <?=$user->displayName()?>
<?php endif; ?>
</a>
