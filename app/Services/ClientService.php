<?php
namespace App\Services;

use App\Models\Client;
use App\Models\ClientClinicianAssign;

class ClientService
{
    /*
     * Return all clients
     * Return assigned clients for clinician if current user is Clinician
     */
    public function getClientsForRole($role)
    {
        return Client::query()
            ->when($role && $role->slug === 'clinician', function ($query) {
                $query->whereIn(
                    'id',
                    ClientClinicianAssign::where('clinician_id', auth()->id())
                        ->whereNull('release_date')
                        ->select(['client_id'])
                        ->pluck('client_id')
                );
            })
            ->latest('id')
            ->get();
    }
}
