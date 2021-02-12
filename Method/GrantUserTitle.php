<?php
namespace GDO\Profile\Method;

use GDO\Form\GDT_Form;
use GDO\Form\MethodForm;
use GDO\Profile\GDT_User;
use GDO\UI\GDT_Title;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Submit;
use GDO\Core\MethodAdmin;
use GDO\Profile\Module_Profile;

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
        $form->actions()->addField(GDT_Submit::make());
    }

    public function formValidated(GDT_Form $form)
    {
        $user = $form->getFormValue('user');
        $title = $form->getFormVar('title');
        Module_Profile::instance()->saveUserSetting($user, 'user_title', $title);
        return $this->message('msg_title_set', [$user->displayNameLabel(), html($title)]);
    }
    
}
