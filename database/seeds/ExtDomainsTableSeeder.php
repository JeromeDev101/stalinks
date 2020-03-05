<?php

use App\Models\Country;
use Illuminate\Database\Seeder;
use App\Models\ExtDomain;

class ExtDomainsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory(App\Models\ExtDomain::class, 10)->create();
       // ExtDomain::where('id', '>', 0)->delete();

        $topsites = str_replace("\r", "", config('alexa.response_example'));
        $topsitesArray = json_decode($topsites, true);

        $dataExtDomain = $topsitesArray["Ats"]["Results"]["Result"]["Alexa"]["TopSites"]["Country"]["Sites"]["Site"];
        $country = Country::where('code', 'VN')->first();

        $index = 1;
        foreach($dataExtDomain as $domain) {
            if (ExtDomain::where('domain', $domain['DataUrl'])->first()) {
                continue;
            }

            $extDomain = ExtDomain::find($index);
            $index++;

            if ($extDomain) {
                $extDomain->domain = $domain['DataUrl'];
                $extDomain->alexa_rank = $domain['Country']['Rank'];
                $extDomain->email = '';
                $extDomain->phone = '';
                $extDomain->facebook = '';
                $extDomain->status = config('constant.EXT_STATUS_NEW');

                $extDomain->save();
                continue;
            }

            ExtDomain::firstOrCreate([
                'id' => $index - 1,
                'domain' => $domain['DataUrl'],
                'alexa_rank' => $domain['Country']['Rank'],
                'country_id' => $country->id,
                'ahrefs_traffic' => 0,
                'ref_domains' => 0,
                'linked_domains' => 0,
                'no_backlinks' => 0,
                'email' => '',
                'phone' => '',
                'facebook' => '',
                'status' => config('constant.EXT_STATUS_NEW'),
                'nopages' => 0,
            ]);
        }
    }
}
