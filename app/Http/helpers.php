<?php

use App\Models\Config;
use Illuminate\Support\Facades\Cache;

if (! function_exists('trim_special_characters')) {
    function trim_special_characters($input)
    {
        return trim($input, " \t\n\r\0\x0B\xA0");
    }
}

if (!function_exists('implode_array_to_strings')) {
    function implode_array_to_strings($arrInput)
    {
        $result = [];
        if (is_array($arrInput)) {
            foreach ($arrInput as $arr) {
                $result[] .= "'". $arr ."'";
            }
        } else {
            $result[] .= "'". $arrInput ."'";
        }

        return $result;
    }
}

if (! function_exists('divnum')) {

    function divnum($numerator, $denominator)
    {
        return $denominator == 0 ? 0 : ($numerator / $denominator);
    }

}

if (!function_exists('move_file_to_storage')) {
    function move_file_to_storage($file, $storagePath, $filename)
    {
        $file->move(public_path($storagePath), $filename);

        return $filename;
    }
}

if (!function_exists('in_array_custom')) {
    function in_array_custom($needle, $haystack)
    {
        return in_array(strtolower($needle), array_map('strtolower', $haystack));
    }
}

if (!function_exists('remove_http')) {

    /**
     * clean url
     *
     * @param $url
     * @return string|string[]
     */
    function remove_http($url) {
        $disallowed = array(
            'http://',
            'https://',
            'http//',
            'https//',
            'https',
            'http',
            'www.',
            '?',
            ':'
        );

        foreach($disallowed as $d) {
            if(strpos($url, $d) !== false) {
                $url = str_replace($d, '', $url);
            }
        }

        // remove www.
        $url = preg_replace('#^www\.(.+\.)#i', '$1', $url);

        // remove path, assuming that url is = sampledomain.com/path/path
        if(strstr($url, '/')){
            $url = substr($url, 0, strpos($url, '/'));
        }

        return $url;
    }
}

if (!function_exists('settings')) {

    /**
     * retrieves a setting saved in config table
     *
     * @param $key
     * @return string|string[]
     */
    function settings($key)
    {
        static $settings;

        Cache::forget('settings');

        if(is_null($settings))
        {
            $settings = Cache::remember('settings', 24*60, function() {
                return array_pluck(Config::all()->toArray(), 'value', 'code');
            });
        }

        return (is_array($key)) ? array_only($settings, $key) : $settings[$key];
    }

}

if (!function_exists('get_buyer_id_with_affiliates')) {

    /**
     * get ids of buyers with affiliate
     *
     * @return string|string[]|array
     */
    function get_buyer_id_with_affiliates()
    {
        static $buyer_with_affiliate_ids;

        Cache::forget('buyer_with_affiliates_ids');

        if(is_null($buyer_with_affiliate_ids))
        {
            $buyer_with_affiliate_ids = Cache::remember('buyer_with_affiliates_ids', 24*60, function() {

                $active_affiliates = \App\Models\User::where('role_id', 11)
                    ->where('status', 'active'
                    )->pluck('id')
                    ->toArray();

                $registration_emails = \App\Models\Registration::select('email')
                    ->whereNotNull('affiliate_id')
                    ->whereIn('affiliate_id', $active_affiliates)
                    ->pluck('email')
                    ->toArray();

                return \App\Models\User::select('id')
                    ->whereIn('email', $registration_emails)
                    ->pluck('id')
                    ->toArray();
            });
        }

        return $buyer_with_affiliate_ids;
    }

}

if (!function_exists('get_affiliate_buyers_user_ids')) {

    /**
     * get ids of buyers with affiliate
     *
     * @param $affiliate_id
     * @return string|string[]|array
     */
    function get_affiliate_buyers_user_ids($affiliate_id)
    {
        static $affiliate_buyers_user_ids;

        Cache::forget('affiliate_buyers_user_ids');

        if(is_null($affiliate_buyers_user_ids))
        {
            $affiliate_buyers_user_ids = Cache::remember('affiliate_buyers_user_ids', 24*60, function() use ($affiliate_id) {

                $affiliate_buyers_registration = \App\Models\Registration::where('affiliate_id', $affiliate_id)
                    ->where('status', 'active')
                    ->pluck('email')
                    ->toArray();

                return \App\Models\User::select('id')
                    ->whereIn('email', $affiliate_buyers_registration)
                    ->pluck('id')
                    ->toArray();
            });
        }

        return $affiliate_buyers_user_ids;
    }

}

if (!function_exists('get_backlink_billing_fees')) {

    /**
     * get ids of buyers with affiliate
     *
     * @return string|string[]|array
     */
    function get_backlink_billing_fees()
    {
        static $backlink_billing_fees;

        Cache::forget('backlink_billing_fees');

        if(is_null($backlink_billing_fees))
        {
            $backlink_billing_fees = Cache::remember('backlink_billing_fees', 24*60, function() {

                return \App\Models\Billing::groupBy('id_backlink')
                    ->selectRaw('sum(fee) as fee, id_backlink')
                    ->pluck('fee','id_backlink')
                    ->filter(function ($value) {
                        return $value > 0;
                    })
                    ->toArray();
            });
        }

        return $backlink_billing_fees;
    }

}
