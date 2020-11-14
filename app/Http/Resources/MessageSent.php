<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Mailgun\Mailgun;
use Carbon\Carbon;

class MessageSent extends JsonResource
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
            if($value->getEnvelope()['sender'] == $this->email)
               
            {
                return [
                    'date'          => Carbon::parse($value->getEventDate())->toDayDateTimeString(),
                    'url'           => $value->getStorage()['url'],
                    'recipient'     => $value->getRecipient(),
                    'records'       => new ShowMessage($this->mg->messages()->show($value->getStorage()['url']))
                    

                ];   
            }
            
        })->filter()->all();
    }
}
