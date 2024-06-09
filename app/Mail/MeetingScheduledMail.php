namespace App\Mail;
use App\Models\leads;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MeetingScheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;

    public function __construct(leads $lead)
    {
        $this->lead = $lead;
    }

    public function build()
    {
        return $this->subject('Meeting Scheduled Confirmation')
        ->with([
                        'leadName' => $this->lead->leadName,
                        'date'=>$this->lead->date,
                    ])
                    ->view('mail.meetingScheduled');
    }
}
