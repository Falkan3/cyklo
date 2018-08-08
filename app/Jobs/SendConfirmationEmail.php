<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use App\User;

class SendConfirmationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The email confirmation token.
     *
     * @var User
     */
    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     */
    public function handle()
    {
        try {
            $this->user->sendEmailConfirmationNotification($this->user->verification_token);
        } catch (\Exception $ex) {
            $this->failed($ex);
        }
    }

    /**
     * Handle a job failure.
     *
     * @param \Exception $ex
     */
    public function failed(\Exception $ex)
    {
        Log::error('Error while sending email confirmation notification. | ' . $ex->getMessage());
    }
}
