<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Messages extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        return $this->map(function( $value ){
            return [
                'data'          => $value->getStorage(),
                'recipient'     => $value->getRecipient()

            ];
        });
    }
}
