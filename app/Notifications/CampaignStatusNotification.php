<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\User;
use App\Mail\CampaignAssignPublisher as AssignCampaign;
use App\Mail\CampaignStatusEmail as StatusEmail;

class CampaignStatusNotification extends Notification
{
  use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
      return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
      $advertiserEmail = $this->get_user_email($notifiable['advertiser_id']);      
      return (\Mail::to($advertiserEmail)->send(new StatusEmail($notifiable)));
    }

    private function get_user_email($user_id){
      $user = User::find($user_id);
      return $user->email;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
      return [
            //
      ];
    }
  }
