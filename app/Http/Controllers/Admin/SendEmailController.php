<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\CentralLogics\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendBulkEmailJob;
use Brian2694\Toastr\Facades\Toastr;
use App\Model\Newsletter;
use App\Mail\Feedback;
use Illuminate\Support\Facades\Validator;

class SendEmailController extends Controller
{
    public function sendBulkEmail(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'recipient' => 'required',
            'emailSubject' => 'required',
            'message' => 'required',
        ]);
    
        if ($validator->fails()) {
            Toastr::error(translate('Failed to sent emails'));
            return back();
        }

        $subscribers = [
            'emails' => $request->input('recipient'),
            'subject' => $request->input('emailSubject'),
            'message' => $request->input('message'),
        ];
    
        // dd($subscribers);
        
        SendBulkEmailJob::dispatch($subscribers);
        Toastr::success(translate('Email sent to all successfully !'));
        return back();
    }
    
    public function adminFeedback(Request $request){
        $validator = Validator::make($request->all(), [
            'feedback' => 'required',
        ]);
    
        if ($validator->fails()) {
            Toastr::error(translate('Failed to sent emails'));
            return back();
        }

        $feedback = [
            'emails' => 'mukhlissse@gmail.com',
            'subject' => '[Important] Feedback from admin',
            'message' => $request->input('feedback'),
        ];
    
 
        try {
            Mail::to($feedback['emails'])->send(new Feedback($feedback['subject'], $feedback['message']));
            Toastr::success(translate('Email sent to all successfully!'));
        } catch (\Exception $e) {
            info($e->getMessage());
            Toastr::error(translate('Failed to send emails'));
        }
        
        return back();
    }
}
