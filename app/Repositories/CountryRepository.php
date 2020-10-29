<?php

namespace App\Repositories;

use App\Models\Country;
use App\Models\ExtDomain;
use App\Models\User;
use App\Models\UserCountry;
use App\Models\UserCountryExt;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\CountryRepositoryInterface;


class CountryRepository extends BaseRepository implements CountryRepositoryInterface
{

    protected $model;

    /**
     * CountryRepository construct
     *
     * @param  mixed $model
     *
     * @return void
     */
    public function __construct(Country $model)
    {
        parent::__construct($model);
    }

    public function filterCountry($userId, $forExt = false)
    {
        // TODO: Implement filterCountry() method.
    }

    public function validCountryIdList($countryIds, $userId, $forExt = false)
    {
        $user = User::find($userId);
//        if ($user->isAdmin()) {
//            return $countryIds;
//        }

        $countries = collect([]);
        if ($forExt === false) {
            $countries = UserCountry::where('user_id', $userId)->whereIn('country_id', $countryIds)->select('country_id')->get();
        } else {
            $countries = UserCountryExt::where('user_id', $userId)->whereIn('country_id', $countryIds)->select('country_id')->get();
        }

        $results = [];
        foreach($countries as $item) {
            $results[] = $item->country_id;
        }

        return $results;
    }

    public function validCountryIdListAllList($countryIds, $userId, $forExt = false)
    {
        $user = User::find($userId);
        if ($user->isAdmin()) {
            return $countryIds;
        }

        $countries = collect([]);
        if ($forExt === false) {
            $countries = UserCountry::where('user_id', $userId)->whereIn('country_id', $countryIds)->select('country_id')->get();
        } else {
            $countries = UserCountryExt::where('user_id', $userId)->whereIn('country_id', $countryIds)->select('country_id')->get();
        }

        $results = [];
        foreach($countries as $item) {
            $results[] = $item->country_id;
        }

        return $results;
    }


    public function validCountryIdListOnlyUser($countryIds, $userId, $forExt = false)
    {
        $user = User::find($userId);
        if (!$user) return [];

        $countries = collect([]);
        if ($forExt === false) {
            $countries = UserCountry::where('user_id', $userId)->whereIn('country_id', $countryIds)->select('country_id')->get();
        } else {
            $countries = UserCountryExt::where('user_id', $userId)->whereIn('country_id', $countryIds)->select('country_id')->get();
        }

        $results = [];
        foreach($countries as $item) {
            $results[] = $item->country_id;
        }

        return $results;
    }


    public function getListCountriesAccess($userId, $forExt = false)
    {
        $countries = collect([]);
        if ($forExt === false) {
            $countries = UserCountry::where('user_id', $userId)->get();
        } else {
            $countries = UserCountryExt::where('user_id', $userId)->get();
        }

        $results = [];
        foreach($countries as $item) {
            $results[] = $item->country_id;
        }

        return $results;
    }

    public function getListCountriesModelAccess($userId, $additionExtIds = [], $forExt = false)
    {
        $countries = collect([]);
        if ($forExt === false) {
            $countries = $this->model->select('countries.id', 'name', 'code')->whereHas('users', function($query) use($userId) {
                $query->where('users.id', $userId);
            })->orderBy('id', 'desc')->get();
        } else {
            $countries = $this->model->select('countries.id', 'name', 'code')->whereHas('extUsers', function($query) use($userId) {
                $query->where('users.id', $userId);
            })->orderBy('id', 'desc')->get();
        }

        if ($forExt == true) {
            $results = [];
            $countriesExt = ExtDomain::select('id', 'country_id')->whereIn('id', $additionExtIds)->get();
            foreach($countriesExt as $item) {
                $results[] = $item->country_id;
            }

            foreach($countries as $item) {
                $results[] = $item->id;
            }

            $countries = $this->model->select('countries.id', 'name', 'code')->whereIn('id', $results)->orderBy('name', 'asc')->get();
        }

        return $countries;
    }

    public function findCountryIdList($countryIds, $userId, $forExt = false)
    {
        $user = User::find($userId);
//        if ($user->isAdmin()) {
//            return $countryIds;
//        }

        $countries = collect([]);
        if ($forExt === false) {
            $countries = $this->model->whereIn('id', $countryIds)->whereHas('usersAccessable', function ($query) use ($userId) {
                return $query->where('users.id', $userId);
            })->get();
        } else {
            $countries = $this->model->whereIn('id', $countryIds)->whereHas('usersExtAccessable', function ($query) use ($userId) {
                return $query->where('users.id', $userId);
            })->get();
        }

        $results = [];
        foreach($countries as $item) {
            $results[] = $item->id;
        }

        return $results;
    }

    public function paginate($page, $perPage, $filters)
    {
        $queryBuilder = $this->buildSimpleFilterQuery($filters);
        $data = $queryBuilder->orderBy('name', 'asc')->paginate($perPage, ['*'], 'page', $page);

        return $data;
    }
}
