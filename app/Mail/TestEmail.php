<?php

namespace App\Mail;
use App\Models\Campaigns;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $campaign;
    public function __construct(Campaigns $campaign)
    {
        //
        $this->campaign = $campaign;
    }

    public function build()
    {
        return $this->view('mail.test')
                    ->subject('Test Email')
                    ->with([
                        'campaignName' => $this->campaign->campaign_name,
                        'campaignDescription' => $this->campaign->description,
                        'startDate'=>$this->campaign->start_date,
                        'endDate'=>$this->campaign->end_date,
                    ]);;
    }
}
