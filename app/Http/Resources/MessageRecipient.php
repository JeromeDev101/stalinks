<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ShowMessage;
use Mailgun\Mailgun;
use Carbon\Carbon;

class MessageRecipient extends JsonResource
{
    private $email;
    private $mg;

    public function __construct($resource, $email) {
            parent::__construct($resource);
            $this->resource = $resource;
            $this->email = $email; 
            $this->mg = Mailgun::create(config('gun.mail_api'));
    }


    public function toArray($request)
    {
        return $this->map(function( $value ){
            if($value->getRecipient() == $this->email)
               
            {
                return [
                    'date'          => Carbon::parse($value->getEventDate())->toDayDateTimeString(),
                    'url'           => $value->getStorage()['url'],
                    'recipient'     => $value->getRecipient(),
                    'records'       => new ShowMessage($this->mg->messages()->show($value->getStorage()['url']))
                    

                ];   
            }
            
        })->filter()->all();;
    }
}
