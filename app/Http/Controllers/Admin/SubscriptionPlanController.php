<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::ordered()->get();
        return view('admin.subscriptions.index', compact('plans'));
    }

    public function create()
    {
        $plan = new SubscriptionPlan([
            'currency'    => 'INR',
            'cta_label'   => 'Register',
            'is_active'   => true,
            'sort_order'  => (int) (SubscriptionPlan::max('sort_order') ?? 0) + 1,
            'features'    => [],
        ]);
        return view('admin.subscriptions.form', [
            'plan'    => $plan,
            'heading' => 'Add Subscription Plan',
            'action'  => route('admin.subscriptions.store'),
            'method'  => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        SubscriptionPlan::create($data);
        return redirect()->route('admin.subscriptions.index')->with('success', 'Plan created.');
    }

    public function edit(SubscriptionPlan $subscription)
    {
        return view('admin.subscriptions.form', [
            'plan'    => $subscription,
            'heading' => 'Edit Subscription Plan',
            'action'  => route('admin.subscriptions.update', $subscription),
            'method'  => 'PUT',
        ]);
    }

    public function update(Request $request, SubscriptionPlan $subscription)
    {
        $data = $this->validated($request, $subscription);
        $subscription->update($data);
        return redirect()->route('admin.subscriptions.index')->with('success', 'Plan updated.');
    }

    public function destroy(SubscriptionPlan $subscription)
    {
        $subscription->delete();
        return back()->with('success', 'Plan deleted.');
    }

    /** Toggle active/inactive via POST — quick switch from the index page. */
    public function toggleActive(SubscriptionPlan $subscription)
    {
        $subscription->is_active = ! $subscription->is_active;
        $subscription->save();
        return back()->with('success',
            $subscription->is_active
                ? "Plan '{$subscription->name}' activated."
                : "Plan '{$subscription->name}' deactivated.");
    }

    /* ---------------- helpers ---------------- */

    protected function validated(Request $request, ?SubscriptionPlan $existing = null): array
    {
        $uniqueSlug = 'unique:subscription_plans,slug';
        if ($existing) $uniqueSlug .= ',' . $existing->id;

        $data = $request->validate([
            'name'            => ['required', 'string', 'max:120'],
            'slug'            => ['nullable', 'string', 'max:120', $uniqueSlug],
            'tagline'         => ['nullable', 'string', 'max:160'],
            'description'     => ['nullable', 'string', 'max:2000'],
            'price_monthly'   => ['required', 'numeric', 'min:0'],
            'price_yearly'    => ['required', 'numeric', 'min:0'],
            'currency'        => ['required', 'string', 'size:3'],
            'save_percent'    => ['nullable', 'integer', 'min:0', 'max:100'],
            'cta_label'       => ['required', 'string', 'max:60'],
            'cta_url'         => ['nullable', 'url', 'max:255'],
            'sort_order'      => ['nullable', 'integer', 'min:0'],
            'free_trial_days' => ['nullable', 'integer', 'min:0'],
            'features'        => ['nullable', 'array'],
            'features.*.text'     => ['nullable', 'string', 'max:200'],
            'features.*.included' => ['nullable'],
        ]);

        // Toggles arrive only when checked — coerce to boolean regardless.
        $data['is_active']     = $request->boolean('is_active');
        $data['is_popular']    = $request->boolean('is_popular');
        $data['is_free_trial'] = $request->boolean('is_free_trial');

        // Auto-slug if blank
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Normalise features: drop blank rows, coerce `included` to bool
        $features = collect($data['features'] ?? [])
            ->map(fn ($f) => [
                'text'     => trim((string) ($f['text'] ?? '')),
                'included' => (bool) ($f['included'] ?? false),
            ])
            ->filter(fn ($f) => $f['text'] !== '')
            ->values()
            ->all();
        $data['features'] = $features;

        return $data;
    }
}
