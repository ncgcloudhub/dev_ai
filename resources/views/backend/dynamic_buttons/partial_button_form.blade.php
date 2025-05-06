@php
    $buttonStyle = $buttons->firstWhere('button_type', $buttonId);
    $bgColor = '#74068f'; // Default background color
    $gradientColor = '#f753a5'; // Default gradient color

    if ($buttonStyle) {
        $classes = json_decode($buttonStyle->classes, true);
        if (isset($classes['background'])) {
            $background = $classes['background'];
            preg_match_all('/#([a-f0-9]{6}|[a-f0-9]{3})/i', $background, $matches);
            if (isset($matches[0][0])) {
                $bgColor = $matches[0][0];
            }
            if (isset($matches[0][1])) {
                $gradientColor = $matches[0][1];
            }
        }
    }
@endphp

<form>
    <div class="mb-3">
        <label class="form-label">Background Color</label>
        <input type="color" id="bgColor-{{ $buttonId }}" class="form-control" value="{{ $bgColor }}" data-button="{{ $buttonId }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Gradient End Color</label>
        <input type="color" id="gradientColor-{{ $buttonId }}" class="form-control" value="{{ $gradientColor }}" data-button="{{ $buttonId }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Border Radius (px)</label>
        <input type="number" id="borderRadius-{{ $buttonId }}" class="form-control" 
               value="{{ $buttonStyle ? intval(str_replace('px', '', json_decode($buttonStyle->classes, true)['border-radius'] ?? '10')) : '10' }}" 
               data-button="{{ $buttonId }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Shadow Intensity</label>
        <input type="range" id="shadowIntensity-{{ $buttonId }}" min="0" max="20" value="{{ $buttonStyle ? intval(str_replace(['px', 'box-shadow: 0 4px ', ' rgba(0, 0, 0, 0.2)'], '', json_decode($buttonStyle->classes, true)['box-shadow'] ?? '4')) : '4' }}" class="form-range" data-button="{{ $buttonId }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Button Text</label>
        <input type="text" id="buttonText-{{ $buttonId }}" class="form-control" value="{{ $buttonStyle ? ucfirst($buttonStyle->button_type) : ucfirst($buttonId) }}" data-button="{{ $buttonId }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Select Icon</label>
        <select id="icon-{{ $buttonId }}" class="form-select" data-button="{{ $buttonId }}">
            <option value="" {{ $buttonStyle && $buttonStyle->icon == '' ? 'selected' : '' }}>None</option>
            <option value="ri-save-3-line" {{ $buttonStyle && $buttonStyle->icon == 'ri-save-3-line' ? 'selected' : '' }}>Save (💾)</option>
            <option value="ri-edit-fill" {{ $buttonStyle && $buttonStyle->icon == 'ri-edit-fill' ? 'selected' : '' }}>Edit (✏️)</option>
            <option value="ri-delete-bin-2-fill" {{ $buttonStyle && $buttonStyle->icon == 'ri-delete-bin-2-fill' ? 'selected' : '' }}>Delete (🗑️)</option>
            <option value="ri-close-circle-fill" {{ $buttonStyle && $buttonStyle->icon == 'ri-close-circle-fill' ? 'selected' : '' }}>Cancel (❌)</option>
            <option value="ri-eye-fill" {{ $buttonStyle && $buttonStyle->icon == 'ri-eye-fill' ? 'selected' : '' }}>View</option>
            <option value="ri-download-2-fill" {{ $buttonStyle && $buttonStyle->icon == 'ri-download-2-fill' ? 'selected' : '' }}>Download</option>
            <option value="ri-file-copy-2-fill" {{ $buttonStyle && $buttonStyle->icon == 'ri-file-copy-2-fill' ? 'selected' : '' }}>Copy</option>
            <option value="las la-file-import" {{ $buttonStyle && $buttonStyle->icon == 'las la-file-import' ? 'selected' : '' }}>Import</option>
            <option value="las la-file-export" {{ $buttonStyle && $buttonStyle->icon == 'las la-file-export' ? 'selected' : '' }}>Export</option>
            <option value="ri-search-line" {{ $buttonStyle && $buttonStyle->icon == 'ri-search-line' ? 'selected' : '' }}>Search</option>
            <option value="ri-filter-line" {{ $buttonStyle && $buttonStyle->icon == 'ri-filter-line' ? 'selected' : '' }}>Filter</option>
            <option value="ri-add-box-fill" {{ $buttonStyle && $buttonStyle->icon == 'ri-add-box-fill' ? 'selected' : '' }}>Add</option>
            <option value="ri-subtract-fill" {{ $buttonStyle && $buttonStyle->icon == 'ri-subtract-fill' ? 'selected' : '' }}>Remove</option>
            <option value="las la-magic" {{ $buttonStyle && $buttonStyle->icon == 'las la-magic' ? 'selected' : '' }}>Generate</option>
        </select>
    </div>

    <button type="button" class="btn gradient-btn-save save-button-style" data-button="{{ $buttonId }}">Save Style</button>
</form>