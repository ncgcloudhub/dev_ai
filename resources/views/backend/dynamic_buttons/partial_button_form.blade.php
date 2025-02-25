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
            <option value="fa-save" {{ $buttonStyle && $buttonStyle->icon == 'fa-save' ? 'selected' : '' }}>Save (💾)</option>
            <option value="fa-edit" {{ $buttonStyle && $buttonStyle->icon == 'fa-edit' ? 'selected' : '' }}>Edit (✏️)</option>
            <option value="fa-trash" {{ $buttonStyle && $buttonStyle->icon == 'fa-trash' ? 'selected' : '' }}>Delete (🗑️)</option>
            <option value="fa-times" {{ $buttonStyle && $buttonStyle->icon == 'fa-times' ? 'selected' : '' }}>Cancel (❌)</option>
        </select>
    </div>

    <button type="button" class="btn btn-primary save-button-style" data-button="{{ $buttonId }}">Save Style</button>
</form>