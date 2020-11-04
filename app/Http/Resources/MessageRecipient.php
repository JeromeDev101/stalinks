<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageRecipient extends JsonResource
{
    private $email;

    public function __construct($resource, $email) {
            parent::__construct($resource);
            $this->resource = $resource;
            $this->email = $email; 
    }


    public function toArray($request)
    {
        return $this->map(function( $value ){
            if($value->getRecipient() == $this->email)
            {
                return [
                    'data'          => $value->getStorage(),
                    'recipient'     => $value->getRecipient()

                ];   
            }
            
        });
    }
}
