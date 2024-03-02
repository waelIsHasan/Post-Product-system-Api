<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Product;
class Invitation extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private Product $product;
    private $username;

    private $user_id;
    public function __construct($product , $username ,$user_id)
    {
        $this->product = $product;
        $this->username= $username;
        $this->user_id = $user_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_creater' => $this->username,
            'user_id'=>$this->user_id,
            'product' => $this->product
        ];
    }
}
