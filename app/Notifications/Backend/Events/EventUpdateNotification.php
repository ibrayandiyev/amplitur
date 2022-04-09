<?php

namespace App\Notifications\Backend\Events;

use App\Models\Client;
use App\Models\Event;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class EventUpdateNotification extends Notification
{
    /**
     * The provider.
     *
     * @var Provider
     */
    public $provider    = null;

    /**
     * The provider.
     *
     * @var Client
     */
    public $client    = null;

    /**
     * The provider.
     *
     * @var Event
     */
    public $event;

    /**
     * The callback that should be used to create the reset password URL.
     *
     * @var \Closure|null
     */
    public static $createUrlCallback;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    /**
     * Create a notification instance.
     *
     * @param  string  $provider
     * @return void
     */
    public function __construct(Model $object, Event $event)
    {
        switch(get_class($object)){
            case Provider::class:
                $this->provider = $object;
                break;
            case Client::class:
                $this->client   = $object;
                break;
        }
        $this->event    = $event;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        if($this->provider != null){
            $mailMessage = (new MailMessage)->subject(Lang::get(__('Event Update Notification')))
            ->view("email.backoffice.events.change_event_provider",
            ["provider" => $this->provider, "event"   => $this->event]);
        }

        if($this->client != null){
            /* Uncomment with carefuly
            $mailMessage = (new MailMessage)->subject(Lang::get(__('Event Update Notification')))
            ->view("email.backoffice.events.change_event_client",
            ["client" => $this->client, "event"   => $this->event]);
            */
        }
        if($mailMessage)
            return $mailMessage;
        
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}
