<?php

namespace App\Notifications;

use App\Models\ReleaseRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Str;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsChannel;
use NotificationChannels\MicrosoftTeams\MicrosoftTeamsMessage;

class MsTeamsNotification extends Notification
{
    use Queueable;

    protected $req_id;
    protected $req_type; // created|updated|approval

    // first param is project. second param is serial
    protected $titles = [
        'created' => '? (New) ?',
        'updated' => '? (Updated) ?',
        'approval' => '? (Approval) ?',
    ];

    // first param is project. second param is user
    protected $messages = [
        'created' => 'New release request created by <b>?</b>.',
        'updated' => 'Release request updated.',
        'approval' => 'Release request approval updated.',
    ];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(int $req_id, string $req_type = 'created')
    {
        $this->req_id = $req_id;
        $this->req_type = $req_type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [MicrosoftTeamsChannel::class];
    }

    public function toMicrosoftTeams($notifiable)
    {
        $releaseReq = ReleaseRequest::query()
            ->with(['project:id,name', 'requested_by:id,name,userid'])
            ->find($this->req_id);

        $url = route('release-requests.edit', ['release_request' => $this->req_id]);
        $serial = "<a href='{$url}'>#{$releaseReq->serial_id}</a>";

        $title = $this->titles[strtolower($this->req_type)];
        $title = Str::replaceArray('?', [$releaseReq->project->name, $serial], $title);

        $message = $this->messages[strtolower($this->req_type)];
        $message = Str::replaceArray('?', [$releaseReq->requested_by->name], $message);

        return MicrosoftTeamsMessage::create()
            ->to(config('services.microsoft_teams.webhook_url'))
            ->type('success')
            ->title($title)
            ->content($message)
            ->button('View', $url);
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
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
