<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShowMessage extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
                'from'          => $this->getFrom(),
                'sender'        => $this->getSender(),
                'bodyPlain'     => $this->getBodyPlain(),
                'strippedText'  => $this->getStrippedText(),
                'strippedHtml'  => $this->getStrippedHtml(),
                'attachment'    => $this->getAttachments()
        ];
    }
}
