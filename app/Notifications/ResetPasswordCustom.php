<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class ResetPasswordCustom extends BaseResetPassword
{
    /**
     * Get the reset password notification mail message for the given URL.
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject(Lang::get('Reset your password for EduSmart'))
            ->markdown('emails.reset-password', ['url' => $url]);
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $url = $this->resetUrl($notifiable);
        
        return (new MailMessage)
            ->subject(Lang::get('Reset your password for EduSmart'))
            ->markdown('emails.reset-password', ['url' => $url, 'notifiable' => $notifiable]);
    }
}
