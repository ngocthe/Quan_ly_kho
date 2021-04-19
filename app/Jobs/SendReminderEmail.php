<?php

namespace App\Jobs;

use App\Models\Setting;
use Bogardo\Mailgun\Facades\Mailgun;
use Bogardo\Mailgun\Mail\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $import;
    public function __construct($import)
    {
        $this->import = $import;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $emails = Setting::where('key', 'emails')->first()->value;
        $recipients = collect(explode(',', $emails))->mapWithKeys(function ($item) {return [$item => ['name' => 'Manager']];})->toArray();
        Mailgun::send('emails.reminder', ['import' => $this->import], function (Message $message) use ($recipients) {
            $message->to($recipients)->subject('Nhắc nhở hàng về');
        });
    }
    public function fail($exception = null)
    {
        Log::error($exception);
    }
}
