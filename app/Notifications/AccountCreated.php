<?php

namespace App\Notifications;

use App\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountCreated extends Notification{

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function via($notifiable){
        return ['mail'];
    }

    public function toMail($notifiable){
        return (new MailMessage())
            ->subject('Sua conta foi criada')
            ->greeting("Olá {$this->user->name}, ")
            ->line("Sua conta foi criada")
            ->action("Acesse este endereço para validá-la", url('/'))
            ->line('Obrigado por usar nossa aplicação')
            ->salutation('Atenciosamente,');
    }

}
