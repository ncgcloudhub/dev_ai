@php
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

$userId = Auth::id();
// Fetch the last 10 activity logs for the logged-in user
$activityLogs = ActivityLog::where('user_id', $userId)
    ->orderBy('created_at', 'desc')
    ->take(10)
    ->get();

    $activityLogsCount = $activityLogs->count();
@endphp

<div class="dropdown topbar-head-dropdown ms-1 header-item">
    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" id="page-header-cart-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="ri-timer-line fs-22"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-xl dropdown-menu-end p-0 dropdown-menu-cart" aria-labelledby="page-header-cart-dropdown">
        <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border bg-primary bg-pattern rounded-top">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="m-0 fs-16 fw-semibold text-white">Recent Activity</h6>
                </div>
                <div class="col-auto">
                    <span class="badge bg-light-subtle text-body fs-13">last <span>{{ $activityLogsCount }}</span>
                        items</span>
                </div>
            </div>
        </div>
        <div class="simplebar-scrollable-y" style="max-height: 300px; overflow-y: auto;">
            <div class="simplebar-content p-2">
                @forelse ($activityLogs as $log)
                <div class="dropdown-item text-wrap px-3 py-2">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mt-0 mb-1 fs-14">
                                <a href="#" class="text-reset">{{ $log->message }}</a>
                            </h6>
                            <p class="mb-0 fs-12 text-muted">
                                {{ $log->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="dropdown-item text-wrap px-3 py-2">
                    No recent activities found.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
