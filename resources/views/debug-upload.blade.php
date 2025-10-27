<!DOCTYPE html>
<html>
<head>
    <title>Debug File Upload</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Debug File Upload</h1>
    <form id="uploadForm" enctype="multipart/form-data">
        @csrf
        <input type="file" name="test_file" id="test_file" accept=".mp4,.mov,.avi,.webm">
        <button type="submit">Test Upload</button>
    </form>
    
    <div id="result"></div>
    
    <script>
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const fileInput = document.getElementById('test_file');
            
            if (!fileInput.files[0]) {
                document.getElementById('result').innerHTML = '<p>Please select a file</p>';
                return;
            }
            
            fetch('/debug-upload', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('result').innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
            })
            .catch(error => {
                document.getElementById('result').innerHTML = '<p>Error: ' + error.message + '</p>';
            });
        });
    </script>
</body>
</html>
