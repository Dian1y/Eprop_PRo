<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WFApprover;
use App\MtlHirarki;
use App\MtlHirarkiStructElement;
use App\TrxDetails;
use App\TrxRekap;
use App\TrxSetApprover;
use App\Events\WorkflowEmail\UserApproverEvent;
use App\Events\SendCMONotif;
use DB;
use Redirect;

class ApproveController extends Controller
{
    Public function CMOApprover($id,$wfkeyid,$webApprv) {

    	$wfApprover = WFApprover::where('id',$id)->first();

        $Message = '';


    	if (($wfApprover->status == 'OPEN' or $wfApprover->status == 'PARTIAL APPROVED')) {
    		if (empty($wfApprover->next_apprv_pos_id)) {

                $wfApprover = DB::table('wf_approval')
                                        ->where('id', '=', $id)
                                        ->first();
                      
                $hirarki_struc_id =  $wfApprover->hirarki_struc_id;
                $cmo_number = $wfApprover->message_name;
                                       
                //WFApprover::where('wf_key_id','=', $wfkeyid)->update(array('mail_status'=>'SENT', 'status'=>'CLOSED'));   

                //TrxRekap::where('cmo_number','=',$cmo_number)->update(array('cmo_status'=>'APPROVED'));

                //TrxSetApprover::where('wf_key_id','=',$wfkeyid)->update(array('status'=>'CLOSED'));

                 $Message = 'Terima Kasih, CMO No. ' .  $wfApprover->message_name . ' Telah di APPROVED';

                 WFApprover::where('id','=', $id)->update(array('action'=>'APPROVED'));
                //Send Notification to Others (Value in FNDValues -- CMO_Notification_Email)

                 $NotifReceive = DB::table('fnd_values')->where('group_value','=','CMO_Notification_Email')->get();

                 $CMOApproved = DB::table('cmo_transaction_view')->where('cmo_number','=',$wfApprover->message_name)->first();

                 $CMOApproved = collect($CMOApproved);
                 $NotifReceive = collect($NotifReceive);


                 $CMOApproved->put('approver',$wfApprover->recipient_name);
                 $CMOApproved->all();


                 foreach ($NotifReceive as $key => $value) {

                     if($value->value_attribute1 == 'EMAIL_TO'){
                            $CMOApproved->put('mail_to',$value->value_name);
                            $CMOApproved->all();
                     } else {
                            $CMOApproved->put('mail_cc',$value->value_name);
                            $CMOApproved->all();
                     }
                 }
                 
                 $CMOApproved->first();
                 
                 event(new SendCMONotif($CMOApproved)) ; 


    		} else {
    		    $position =  $wfApprover->recipient_pos_id;
    		    $person_id = $wfApprover->recipient_id;
    		    $subject = $wfApprover->subject;
    		    $message = $wfApprover->message_name;
    		    $attach_file = $wfApprover->attach_file;
                $idhirarki = $wfApprover->hirarki_id;

                $Message = 'Terima Kasih. CMO No. ' . $wfApprover->message_name . ' Telah di Approved' ;

                WFApprover::where('id','=', $id)->update(array('action'=>'APPROVED'));

                $hirarki_element = MtlHirarkiStructElement::where('hirarki_id','=',$idhirarki)->first();

    		    $cust_ship_id = $hirarki_element->cust_ship_id;    		  

    		    $wf_check = DB::select('Select * From insert_next_approvers(?,?,?,?,?,?,?,?)',[$person_id, $idhirarki,$position,$cust_ship_id,$subject, $message, $attach_file, $wfkeyid]);
                        
        		$wfApprover = DB::table('wf_approval')
        			             ->where('sender_pos_id', '=', $position)
        			             ->where('wf_key_id', '=', $wfkeyid)
        			             ->get();
      
                foreach ($wfApprover as $key => $wfApprv) {      

                        TrxRekap::where('cmo_number', '=',$wfApprv->message_name)->update(array('cmo_status'=>'PARTIAL APPROVED'));
        			    event(new UserApproverEvent($wfApprv)) ;        			             
                        WFApprover::where('wf_key_id','=', $wfkeyid)->update(array('mail_status'=>'SENT', 'status'=>'PARTIAL APPROVED'));   
                
                 } 
    		}

    	}  else {

               $Message = 'CMO No. ' . $wfApprover->message_name . ' Telah di ' .  $wfApprover->status ;
        }		  	

        if (empty($Message)) {
           
        }
        
        if ($webApprv == 'N') {
                return view('email.action.approved')->with('Message', $Message);       
        } else { 

                return Redirect::back()->with('Message', $Message);
        }



    }

    Public function CMORejected($id,$wfkeyid,$webApprv) {

        $wfApprover = WFApprover::find($id)->first();

       

        if ($wfApprover->status == 'OPEN' or $wfApprover->status == 'PARTIAL APPROVED') {
        
                WFApprover::where('id','=', $wfApprover->id)->update(array('status'=>'REJECTED'));
         
                TrxRekap::where('cmo_number','=',$wfApprover->message_name)->update(array('cmo_status'=>'REJECTED'));
        
                TrxSetApprover::where('wf_key_id','=',$wfkeyid)->update(array('status'=>'CLOSED'));
           
        
                $Message = 'CMO No. ' . $wfApprover->message_name . ' Telah di Reject';
            }

        if (empty($Message)) {
            $Message = 'CMO No. ' .  $wfApprover->message_name . ' Telah di ' .  $wfApprover->status ;
        }

        if ($webApprv == 'N') {
                return view('email.action.approved')->with('ApprvMessage', $Message);       
        } else { 
            
                return Redirect::back()->with('Message', $Message);
        } 

    }
}
