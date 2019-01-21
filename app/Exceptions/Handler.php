<?php

namespace App\Exceptions;

//namespace App\Http\Controllers;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use View;
use Illuminate\Contracts\Mail\Mailer;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Intervention\Image\Exception\NotReadableException;

class Handler extends ExceptionHandler {

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        //'Symfony\Component\HttpKernel\Exception\HttpException',
        'Symfony\Component\HttpKernel\Exception\NotFoundHttpException',        
        'Symfony\Component\HttpKernel\Exception\HttpException',
        'Intervention\Image\Exception\NotReadableException'
        //NotFoundHttpException
        
    ];
    protected $mailer;

    public function __construct(LoggerInterface $log, Mailer $mailer) {

        $this->log = $log;
        $this->mailer = $mailer;
    }

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e) {
        return parent::report($e);
    }

    /** Mail exception to developer and manager of project.
     *
     * Created On: 1-aprial-2016
     *
     * @param  \Exception  $e
     * @return void
     */
    public function sendException($msg) {
        //echo 'sss';
        //$email = array('sanjeev.rajput@icreon.com', 'niteshk@icreon.com');
        $email = array('vignesh.t@sedinfotech.ch');
        $emailContent['body'] = $msg;
        $addressData['from_email'] = 'info@sats.com';
        $addressData['from_name'] = 'SATS';
        try {

            $this->mailer->send('emails.default', ['body' => $emailContent['body']], function($message) use ($emailContent) {
                //$message->to('sanjeev.rajput@icreon.com', 'Sanjeev')->cc(array('gaurav.shrivastava@icreon.com', 'niteshk@icreon.com')); 
                $message->to('vignesh.t@sedinfotech.ch', 'Sanjeev'); 
                $message->from('info@sats.com', 'SATS');
                $message->subject("Sats Error");
            });
        } catch (\Exception $e) {
            
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e) {
        if (!$e instanceof NotFoundHttpException && !$e instanceof NotReadableException) {
            $this->sendException($e);
        }

        //return response()->view('errors.exception');
        //return view('errors.404');
        if ($e instanceof ModelNotFoundException) {
            abort(404);
        }
        return parent::render($request, $e);
    }

}
