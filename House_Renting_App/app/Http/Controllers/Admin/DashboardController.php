<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
 public function index()
    {
        $totalUsers = User::count();

        $roles = Role::withCount('users')->get(); // users_count

        $statusCounts = Status::withCount('users')->get()
            ->mapWithKeys(fn($s) => [$s->type => $s->users_count]);

        $activatedUsers   = $statusCounts['accepted'] ?? 0;
        $unactivatedUsers = $statusCounts['pending'] ?? 0;
        $bannedUsers      = $statusCounts['blocked'] ?? 0;

        $housesTotal = null;
        $housesBreakdown = [];

        if (Schema::hasTable('houses')) {
            $housesTotal = DB::table('houses')->count();

            if (Schema::hasColumn('houses', 'status_id')) {
                $housesBreakdown = DB::table('houses')
                    ->join('statuses', 'houses.status_id', '=', 'statuses.id')
                    ->select('statuses.type', DB::raw('COUNT(*) as c'))
                    ->groupBy('statuses.type')
                    ->pluck('c', 'type')
                    ->toArray();
            } elseif (Schema::hasColumn('houses', 'status')) {
                $housesBreakdown = DB::table('houses')
                    ->select('status', DB::raw('COUNT(*) as c'))
                    ->groupBy('status')
                    ->pluck('c', 'status')
                    ->toArray();
            }
        }

        $bookingsTotal = null;
        if (Schema::hasTable('bookings')) {
            $bookingsTotal = DB::table('bookings')->count();
        }

        $days = collect(range(13, 0))->map(fn($i) => now()->subDays($i)->format('Y-m-d'));

        $raw = User::selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->groupBy('d')
            ->pluck('c', 'd');

        $chartLabels = $days->values();
        $chartData = $days->map(fn($d) => (int) ($raw[$d] ?? 0))->values();

        return view('admin.dashboard.welcome', compact(
            'totalUsers','roles',
            'activatedUsers','unactivatedUsers','bannedUsers',
            'housesTotal','housesBreakdown',
            'bookingsTotal',
            'chartLabels','chartData'
        ));
    }
}