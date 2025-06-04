<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Tenant;
use App\Models\TenantRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
     public function index()
    {
        $user_id = Auth::id();

        $totalProperties = Property::where('user_id', $user_id)->count();
        $availableProperties = Property::where('user_id', $user_id)
            ->where('availability', true)
            ->count();
        $unavailableProperties = Property::where('user_id', $user_id)
            ->where('availability', false)
            ->count();

            $totalTenants = Tenant::whereHas('rooms', function($query) use ($user_id) {
            $query->whereHas('property', function($propertyQuery) use ($user_id) {
                $propertyQuery->where('user_id', $user_id);
            });
        })->count();

        // Locataires actifs
        $activeTenants = TenantRoom::where('status', 'active')
            ->whereHas('room', function($query) use ($user_id) {
                $query->whereHas('property', function($propertyQuery) use ($user_id) {
                    $propertyQuery->where('user_id', $user_id);
                });
            })
            ->distinct('tenant_id')
            ->count('tenant_id');

        // Locataires inactifs
        $inactiveTenants = $totalTenants - $activeTenants;

        return view('ownersite.index', compact(
            'totalProperties',
            'availableProperties',
            'unavailableProperties',
              'totalTenants',
            'activeTenants',
            'inactiveTenants'
        ));
    }
}
