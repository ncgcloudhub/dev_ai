<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Image</title>
</head>
<body>
    <h1>Generate Image</h1>
    <form action="{{ route('stable.image') }}" method="POST">
        @csrf
        <label for="prompt">Prompt:</label>
        <input type="text" name="prompt" id="prompt" required>

        <label for="width">Width (optional):</label>
        <input type="number" name="width" id="width" placeholder="512">

        <label for="height">Height (optional):</label>
        <input type="number" name="height" id="height" placeholder="512">

        <label for="steps">Steps (optional):</label>
        <input type="number" name="steps" id="steps" placeholder="50">

        <button type="submit">Generate Image</button>
    </form>

    <div id="result"></div>
</body>

<script>
    document.querySelector('form').addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(e.target);
        const response = await fetch("{{ route('stable.image') }}", {
            method: "POST",
            body: formData,
        });

        const result = await response.json();
        const resultDiv = document.getElementById('result');

        if (result.image_base64) {
            resultDiv.innerHTML = `<img src="data:image/png;base64,${result.image_base64}" alt="Generated Image">`;
        } else if (result.error) {
            resultDiv.innerHTML = `<p>Error: ${result.error}</p>`;
        }
    });
</script>


</html>
