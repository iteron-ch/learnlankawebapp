<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Repositories\EmailRepository;
use Illuminate\Contracts\Bus\SelfHandling;

class SendMail extends Job implements SelfHandling
{
    /**
     * Email parameter variables
     * @var type 
     */
    protected $emailParam;

    /**
     * Create a new SendMailCommand instance.
     * @param type $emailParam
     * @return void
     */
    public function __construct($emailParam)
    {
        $this->emailParam = $emailParam;
    }

    /**
     * Execute the job.
     *
     * @param  Mailer  $mailer
     * @return void
     */
    public function handle(EmailRepository $emailRepo)
    {
        $emailRepo->sendEmail($this->emailParam, 1);
    }
}
