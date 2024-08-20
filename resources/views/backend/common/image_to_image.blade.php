<div class="tab-pane active" id="pill-justified-image-to-image" role="tabpanel">
    <form id="image-upload-form" enctype="multipart/form-data">
        @csrf
        <label for="image">Upload an image:</label>
        <input type="file" id="image" name="image" accept="image/*" required>
        <button type="submit">Generate Image Variation</button>
</form>
<div id="generated-image-container">
    <!-- The generated image will be displayed here -->
</div>
</div>

