@extends('layouts.admin')

@section('admin-content')
<div class="page-head" style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:16px">
    <h1 class="page-title" style="margin:0">Subscription Plans</h1>
    <a href="{{ route('admin.subscriptions.create') }}" class="btn btn-primary-admin">
        <i class="fa-solid fa-plus"></i> Add Plan
    </a>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th style="width:50px">#</th>
                <th>Plan</th>
                <th>Price (Monthly)</th>
                <th>Price (Yearly)</th>
                <th>Features</th>
                <th>Badges</th>
                <th style="width:120px">Status</th>
                <th style="width:190px">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($plans as $plan)
            <tr>
                <td>{{ $plan->sort_order }}</td>
                <td>
                    <strong>{{ $plan->name }}</strong>
                    @if ($plan->tagline)
                        <div><small style="color:var(--muted)">{{ $plan->tagline }}</small></div>
                    @endif
                    <div><code style="font-size:11px">{{ $plan->slug }}</code></div>
                </td>
                <td>
                    {{ $plan->currency }} {{ number_format((float) $plan->price_monthly, 0) }}
                </td>
                <td>
                    {{ $plan->currency }} {{ number_format((float) $plan->price_yearly, 0) }}
                    @if ($plan->save_percent)
                        <div><small style="color:#ea580c">Save {{ $plan->save_percent }}%</small></div>
                    @endif
                </td>
                <td>
                    @php
                        $fs = $plan->features ?? [];
                        $included = collect($fs)->where('included', true)->count();
                        $total = count($fs);
                    @endphp
                    <span title="{{ $included }} included of {{ $total }}">
                        <i class="fa-solid fa-check" style="color:#16a34a"></i> {{ $included }}
                        /
                        <span style="color:var(--muted)">{{ $total }}</span>
                    </span>
                </td>
                <td>
                    @if ($plan->is_popular)
                        <span class="badge badge-popular"><i class="fa-solid fa-crown"></i> Popular</span>
                    @endif
                    @if ($plan->is_free_trial)
                        <span class="badge badge-trial"><i class="fa-solid fa-gift"></i> Free trial</span>
                    @endif
                </td>
                <td>
                    <form method="POST" action="{{ route('admin.subscriptions.toggle', $plan) }}" style="display:inline">
                        @csrf
                        <button type="submit" class="toggle-pill {{ $plan->is_active ? 'on' : 'off' }}"
                                title="Click to {{ $plan->is_active ? 'deactivate' : 'activate' }}">
                            <span class="toggle-dot"></span>
                            <span class="toggle-label">{{ $plan->is_active ? 'Active' : 'Inactive' }}</span>
                        </button>
                    </form>
                </td>
                <td class="row-actions">
                    <a class="btn btn-sm" href="{{ route('admin.subscriptions.edit', $plan) }}">
                        <i class="fa-solid fa-pen"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('admin.subscriptions.destroy', $plan) }}"
                          data-confirm="Delete this plan? This cannot be undone." style="display:inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="8" style="text-align:center;color:#94a3b8;padding:24px">
                No subscription plans yet. <a href="{{ route('admin.subscriptions.create') }}">Add the first one</a>.
            </td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<style>
/* Small admin helpers for this page */
.badge{display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:700;padding:3px 8px;border-radius:999px;margin-right:4px}
.badge-popular{background:#fff7ed;color:#c2410c;border:1px solid #fed7aa}
.badge-trial{background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0}
.toggle-pill{display:inline-flex;align-items:center;gap:8px;padding:5px 12px 5px 6px;border-radius:999px;border:1px solid transparent;background:#f3f4f6;cursor:pointer;font-size:12px;font-weight:600;color:#374151;transition:all .2s}
.toggle-pill .toggle-dot{width:16px;height:16px;border-radius:50%;background:#9ca3af;transition:background .2s,transform .2s}
.toggle-pill.on{background:#dcfce7;border-color:#86efac;color:#166534}
.toggle-pill.on .toggle-dot{background:#16a34a;transform:translateX(2px)}
.toggle-pill.off{background:#fee2e2;border-color:#fca5a5;color:#991b1b}
.toggle-pill.off .toggle-dot{background:#dc2626}
.toggle-pill:hover{filter:brightness(0.98)}
</style>
@endsection
