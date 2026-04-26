@extends('layouts.admin')

@section('admin-content')
<div class="page-head" style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:16px">
    <h1 class="page-title" style="margin:0">{{ $heading }}</h1>
    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-sm">
        <i class="fa-solid fa-arrow-left"></i> Back to plans
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
@endif

<form method="POST" action="{{ $action }}" id="planForm">
    @csrf
    @if ($method === 'PUT') @method('PUT') @endif

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:20px">
        <!-- Main -->
        <div class="card">
            <h3 style="margin-top:0">Plan details</h3>

            <div class="form-row">
                <div class="form-group">
                    <label>Name *</label>
                    <input type="text" name="name" required value="{{ old('name', $plan->name) }}">
                </div>
                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $plan->slug) }}" placeholder="auto-generated from name">
                    <div class="help">Used internally; leave blank to auto-generate.</div>
                </div>
            </div>

            <div class="form-group">
                <label>Tagline <small>(e.g. "Most Popular", "7-Day Free Trial")</small></label>
                <input type="text" name="tagline" value="{{ old('tagline', $plan->tagline) }}">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3">{{ old('description', $plan->description) }}</textarea>
            </div>

            <h3>Pricing</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>Monthly price *</label>
                    <input type="number" name="price_monthly" step="0.01" min="0" required
                           value="{{ old('price_monthly', $plan->price_monthly) }}">
                </div>
                <div class="form-group">
                    <label>Yearly price *</label>
                    <input type="number" name="price_yearly" step="0.01" min="0" required
                           value="{{ old('price_yearly', $plan->price_yearly) }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Currency *</label>
                    <input type="text" name="currency" maxlength="3" required
                           value="{{ old('currency', $plan->currency ?? 'INR') }}">
                    <div class="help">3-letter ISO code (INR, USD, etc.)</div>
                </div>
                <div class="form-group">
                    <label>Yearly "Save %" badge</label>
                    <input type="number" name="save_percent" min="0" max="100"
                           value="{{ old('save_percent', $plan->save_percent) }}"
                           placeholder="e.g. 40 (leave blank to hide)">
                </div>
            </div>

            <h3>Call to action</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>Button label *</label>
                    <input type="text" name="cta_label" required
                           value="{{ old('cta_label', $plan->cta_label ?? 'Register') }}">
                </div>
                <div class="form-group">
                    <label>Button URL</label>
                    <input type="url" name="cta_url"
                           value="{{ old('cta_url', $plan->cta_url) }}"
                           placeholder="https://app.aigrowbot.com/register">
                </div>
            </div>

            <h3>Features</h3>
            <p class="help" style="margin-top:-10px">Add a row per feature. Check the box to mark it as included; uncheck to show it as not-available (crossed).</p>
            <div id="featuresWrap">
                @php
                    $features = old('features', $plan->features ?? []);
                    if (empty($features)) $features = [['text' => '', 'included' => true]];
                @endphp
                @foreach ($features as $i => $f)
                    <div class="feature-row">
                        <label class="feat-check">
                            <input type="hidden" name="features[{{ $i }}][included]" value="0">
                            <input type="checkbox" name="features[{{ $i }}][included]" value="1"
                                   {{ ! empty($f['included']) ? 'checked' : '' }}>
                            <span>Included</span>
                        </label>
                        <input type="text" name="features[{{ $i }}][text]" value="{{ $f['text'] ?? '' }}" placeholder="Feature description">
                        <button type="button" class="btn btn-sm btn-danger feat-remove" title="Remove">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endforeach
            </div>
            <button type="button" id="addFeature" class="btn btn-sm" style="margin-top:8px">
                <i class="fa-solid fa-plus"></i> Add feature
            </button>
        </div>

        <!-- Sidebar -->
        <div class="card">
            <h3 style="margin-top:0">Status</h3>

            <label class="switch-field">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1"
                       {{ old('is_active', $plan->is_active ?? true) ? 'checked' : '' }}>
                <span class="switch-ui"></span>
                <span>Active (visible on pricing page)</span>
            </label>

            <label class="switch-field">
                <input type="hidden" name="is_popular" value="0">
                <input type="checkbox" name="is_popular" value="1"
                       {{ old('is_popular', $plan->is_popular ?? false) ? 'checked' : '' }}>
                <span class="switch-ui"></span>
                <span>Mark as "Most Popular"</span>
            </label>

            <label class="switch-field">
                <input type="hidden" name="is_free_trial" value="0">
                <input type="checkbox" name="is_free_trial" value="1"
                       {{ old('is_free_trial', $plan->is_free_trial ?? false) ? 'checked' : '' }}>
                <span class="switch-ui"></span>
                <span>Free-trial plan</span>
            </label>

            <div class="form-group" style="margin-top:14px">
                <label>Free-trial days</label>
                <input type="number" name="free_trial_days" min="0"
                       value="{{ old('free_trial_days', $plan->free_trial_days) }}"
                       placeholder="e.g. 7 (only when free-trial is on)">
            </div>

            <div class="form-group">
                <label>Sort order</label>
                <input type="number" name="sort_order" min="0"
                       value="{{ old('sort_order', $plan->sort_order ?? 0) }}">
                <div class="help">Lower numbers appear first.</div>
            </div>

            <hr style="margin:18px 0;border:0;border-top:1px solid var(--border)">

            <div style="display:flex;gap:8px">
                <button type="submit" class="btn btn-primary-admin" style="flex:1">
                    <i class="fa-solid fa-floppy-disk"></i> Save plan
                </button>
                <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-sm">Cancel</a>
            </div>
        </div>
    </div>
</form>

<style>
.feature-row{display:flex;gap:10px;align-items:center;margin-bottom:8px}
.feature-row input[type=text]{flex:1;padding:9px 12px;border:1px solid var(--border);border-radius:8px;font-size:13px}
.feat-check{display:inline-flex;align-items:center;gap:6px;font-size:12.5px;color:#374151;white-space:nowrap;background:#f9fafb;border:1px solid var(--border);padding:6px 10px;border-radius:8px;cursor:pointer;min-width:118px}
.feat-check input[type=checkbox]{margin:0}
.switch-field{display:flex;align-items:center;gap:10px;padding:10px 0;font-size:13px;cursor:pointer}
.switch-field input[type=checkbox]{display:none}
.switch-ui{width:36px;height:20px;border-radius:999px;background:#d1d5db;position:relative;flex-shrink:0;transition:background .2s}
.switch-ui::after{content:"";position:absolute;top:2px;left:2px;width:16px;height:16px;border-radius:50%;background:#fff;transition:transform .2s;box-shadow:0 1px 3px rgba(0,0,0,.15)}
.switch-field input[type=checkbox]:checked ~ .switch-ui{background:#ff6b35}
.switch-field input[type=checkbox]:checked ~ .switch-ui::after{transform:translateX(16px)}
</style>

<script>
(function () {
    var wrap = document.getElementById('featuresWrap');
    var addBtn = document.getElementById('addFeature');
    if (!wrap || !addBtn) return;

    function reindex() {
        Array.from(wrap.children).forEach(function (row, idx) {
            row.querySelectorAll('input[name^="features["]').forEach(function (el) {
                el.name = el.name.replace(/features\[\d+\]/, 'features[' + idx + ']');
            });
        });
    }

    addBtn.addEventListener('click', function () {
        var idx = wrap.children.length;
        var div = document.createElement('div');
        div.className = 'feature-row';
        div.innerHTML =
            '<label class="feat-check">' +
              '<input type="hidden" name="features[' + idx + '][included]" value="0">' +
              '<input type="checkbox" name="features[' + idx + '][included]" value="1" checked>' +
              '<span>Included</span>' +
            '</label>' +
            '<input type="text" name="features[' + idx + '][text]" placeholder="Feature description">' +
            '<button type="button" class="btn btn-sm btn-danger feat-remove"><i class="fa-solid fa-xmark"></i></button>';
        wrap.appendChild(div);
        div.querySelector('input[type=text]').focus();
    });

    wrap.addEventListener('click', function (e) {
        var btn = e.target.closest('.feat-remove');
        if (!btn) return;
        e.preventDefault();
        var row = btn.closest('.feature-row');
        if (row) {
            row.parentNode.removeChild(row);
            reindex();
        }
    });
})();
</script>
@endsection
