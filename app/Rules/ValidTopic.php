<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidTopic implements Rule
{
    protected $key, $list;

    /**
     * Create a new rule instance.
     *
     * @param $key
     * @param $list
     */
    public function __construct($key, $list)
    {
        $this->key = $key;
        $this->list = $list;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $result = false;

        // list of topics
        $topic_list = $this->list;

        // remove spaces
        $topic = preg_replace('/\s+/', '', $value);

        // checking if has comma
        if( strpos($value, ',') !== false ) {

            $_topics = explode(',', $value);
            $_topic_result = [];

            //checking if one of the topic is exist or not exist
            foreach($_topics as $_topic){
                if(preg_grep("/" . $_topic . "/i", $topic_list)){
                    array_push($_topic_result, true);
                } else {
                    array_push($_topic_result, false);
                }
            }

            if(!in_array(false, $_topic_result, true)) {
                $result = true;
            }

        } else { // no commas or single topic

            if(preg_grep("/" . $topic . "/i", $topic_list)){
                $result = true;
            }
        }

        return $result;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'No topic name of: :input . Check in row ' . ($this->key + 2);
    }
}
