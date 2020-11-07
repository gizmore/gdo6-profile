<?php
namespace GDO\Profile\Method;

use GDO\Form\GDT_Form;
use GDO\Form\MethodForm;
use GDO\Profile\GDT_User;
use GDO\UI\GDT_Title;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Submit;
use GDO\User\GDO_UserSetting;
use GDO\Core\MethodAdmin;

/**
 * Grant a user a title.
 * @author gizmore
 * @version 6.10
 * @since 6.10
 */
final class GrantUserTitle extends MethodForm
{
    use MethodAdmin;
    
    public function createForm(GDT_Form $form)
    {
        $form->addField(GDT_User::make('user'));
        $form->addField(GDT_Title::make('title'));
        $form->addField(GDT_AntiCSRF::make());
        $form->addField(GDT_Submit::make());
    }

    public function formValidated(GDT_Form $form)
    {
        $user = $form->getFormValue('user');
        $title = $form->getFormVar('title');
        GDO_UserSetting::userSet($user, 'user_title', $title);
        return $this->message('msg_title_set', [$user->displayNameLabel(), html($title)]);
    }
    
}
