<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification; // On garde celui-là pour l'héritage (extends)
use App\Models\Notification as NotificationModel; // On donne un surnom à ton modèle
use Illuminate\Notifications\Messages\MailMessage;

class EmergencyBloodAlert extends Notification
{
    use Queueable;

    protected $bloodRequest;
    protected $center;

    public function __construct($bloodRequest, $center)
    {
        $this->bloodRequest = $bloodRequest;
        $this->center = $center;
        $prefixe = $this->bloodRequest->priority;
    }


    public function via($notifiable)
    {

        if ($this->bloodRequest->priority === 'Urgent') {
            $prefixe = "🚨 [URGENT] ";
            $dateBesoin = now()->format('d/m/Y à H:i');
        } else {
            $prefixe = "📢 [NORMAL] ";
            
            $dateBesoin = now()->addDays(3)->format('d/m/Y');
        }


        NotificationModel::create([
            'user_id'   => $notifiable->id,
            'center_id' => $this->center->id,
            'message'   => $prefixe . "Besoin de sang " . $this->bloodRequest->blood_group . " pour le " . $dateBesoin,
            'sent_at'   => now(),
            'status'    => null,
        ]);

        return [mail];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
