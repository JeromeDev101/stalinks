<?php

namespace App\Services;

use App\Models\Backlink;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\BackLinkRepositoryInterface;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserService
 *
 * @package App\Services
 */
class UserService
{

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var BackLinkRepositoryInterface
     */
    private $backLinkRepository;


    /**
     * UserService constructor.
     *
     * @param UserRepositoryInterface $userRepository
     * @param BackLinkRepositoryInterface $backLinkRepository
     */
    public function __construct(UserRepositoryInterface $userRepository, BackLinkRepositoryInterface $backLinkRepository) {
        $this->userRepository = $userRepository;
        $this->backLinkRepository = $backLinkRepository;
    }

    public function checkUserId($id)
    {
        $userId = Auth::id();

        if (Auth::user()->isAdmin() && isset($id) && $id > 0) {
            $userId = $id;
        }

        return $userId;
    }

    public function findAccessByCountry()
    {
        $countryIds = [];
        $userId = Auth::id();
        foreach (Auth::user()->countriesAccessable as $country) {
            $countryIds[] = $country->id;
        }

        return $countryIds;
    }

    public function findAccessByCountryByUserId($userId)
    {
        $countryIds = [];
        $user = $this->userRepository->findById($userId);

        if (!$user) return [];
        
        foreach ($user->countriesAccessable as $country) {
            $countryIds[] = $country->id;
        }

        return $countryIds;
    }

    public function findAccessByIntDomain()
    {
        $intDomainIds = [];
        $userId = Auth::id();
        foreach (Auth::user()->internalDomainsAccessable as $int) {
            $intDomainIds[] = $int->id;
        }

        return $intDomainIds;
    }

    public function findAccessByIntDomainEmployee($employeeId)
    {
        $intDomainIds = [];
        $userId = Auth::id();

        if (Auth::user()->isAdmin() && isset($employeeId) && $employeeId !== 0) {
            $userId = $employeeId;
        }

        $user = $this->userRepository->findById($userId);
        foreach ($user->internalDomainsAccessable as $int) {
            $intDomainIds[] = $int->id;
        }

        return $intDomainIds;
    }

    public function findCountriesByIntDomainEmployee($employeeId)
    {
        $countriesIds = [];
        $userId = Auth::id();

        if (Auth::user()->isAdmin() && isset($employeeId) && $employeeId !== 0) {
            $userId = $employeeId;
        }

        $user = $this->userRepository->findById($userId);
        foreach ($user->internalDomainsAccessable as $int) {
            $countriesIds[] = $int->country_id;
        }

        return $countriesIds;
    }

    public function findExtDomainIdsFromInt($employeeId) {
        $intDomainIds = $this->findAccessByIntDomainEmployee($employeeId);

        return $this->backLinkRepository->getExtIdsFromInt($intDomainIds);
    }
}
