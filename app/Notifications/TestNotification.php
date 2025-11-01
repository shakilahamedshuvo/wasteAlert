namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TestNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['broadcast'];
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => '🎉 Test notification works!',
        ]);
    }
    }
}
