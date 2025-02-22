<form>
    <div class="mb-3">
        <label class="form-label">Background Color</label>
        <input type="color" id="bgColor-{{ $buttonId }}" class="form-control" value="#74068f" data-button="{{ $buttonId }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Gradient End Color</label>
        <input type="color" id="gradientColor-{{ $buttonId }}" class="form-control" value="#f753a5" data-button="{{ $buttonId }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Border Radius (px)</label>
        <input type="number" id="borderRadius-{{ $buttonId }}" class="form-control" value="10" data-button="{{ $buttonId }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Shadow Intensity</label>
        <input type="range" id="shadowIntensity-{{ $buttonId }}" min="0" max="20" value="4" class="form-range" data-button="{{ $buttonId }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Button Text</label>
        <input type="text" id="buttonText-{{ $buttonId }}" class="form-control" value="{{ ucfirst($buttonId) }}" data-button="{{ $buttonId }}">
    </div>

    <div class="mb-3">
        <label class="form-label">Select Icon</label>
        <select id="icon-{{ $buttonId }}" class="form-select" data-button="{{ $buttonId }}">
            <option value="fa-save">Save (💾)</option>
            <option value="fa-edit">Edit (✏️)</option>
            <option value="fa-trash">Delete (🗑️)</option>
            <option value="fa-times">Cancel (❌)</option>
        </select>
    </div>

    <button type="button" class="btn btn-primary save-button-style" data-button="{{ $buttonId }}">Save Style</button>
</form>