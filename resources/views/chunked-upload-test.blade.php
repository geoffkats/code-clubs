<!DOCTYPE html>
<html>
<head>
    <title>Chunked Video Upload Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .upload-area { border: 2px dashed #ccc; padding: 20px; text-align: center; margin: 20px 0; }
        .progress-bar { width: 100%; height: 20px; background: #f0f0f0; border-radius: 10px; overflow: hidden; margin: 10px 0; }
        .progress-fill { height: 100%; background: #4CAF50; width: 0%; transition: width 0.3s; }
        .status { margin: 10px 0; font-weight: bold; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>Chunked Video Upload Test</h1>
    
    <div class="upload-area" id="uploadArea">
        <p>Drag and drop a video file here or click to select</p>
        <input type="file" id="fileInput" accept="video/*" style="display: none;">
        <button onclick="document.getElementById('fileInput').click()">Select Video File</button>
    </div>
    
    <div class="progress-bar" id="progressBar" style="display: none;">
        <div class="progress-fill" id="progressFill"></div>
    </div>
    
    <div class="status" id="status">Ready to upload</div>
    
    <div id="result"></div>
    
    <script>
        const fileInput = document.getElementById('fileInput');
        const uploadArea = document.getElementById('uploadArea');
        const progressBar = document.getElementById('progressBar');
        const progressFill = document.getElementById('progressFill');
        const status = document.getElementById('status');
        const result = document.getElementById('result');
        
        let currentFile = null;
        let chunkSize = 1024 * 1024; // 1MB chunks
        let currentChunk = 0;
        let totalChunks = 0;
        
        fileInput.addEventListener('change', handleFileSelect);
        uploadArea.addEventListener('dragover', handleDragOver);
        uploadArea.addEventListener('drop', handleDrop);
        
        function handleFileSelect(e) {
            const file = e.target.files[0];
            if (file) {
                startUpload(file);
            }
        }
        
        function handleDragOver(e) {
            e.preventDefault();
            uploadArea.style.borderColor = '#4CAF50';
        }
        
        function handleDrop(e) {
            e.preventDefault();
            uploadArea.style.borderColor = '#ccc';
            const file = e.dataTransfer.files[0];
            if (file) {
                startUpload(file);
            }
        }
        
        function startUpload(file) {
            currentFile = file;
            totalChunks = Math.ceil(file.size / chunkSize);
            currentChunk = 0;
            
            status.textContent = `Starting upload of ${file.name} (${formatFileSize(file.size)})`;
            progressBar.style.display = 'block';
            progressFill.style.width = '0%';
            
            uploadChunk();
        }
        
        function uploadChunk() {
            if (currentChunk >= totalChunks) {
                status.textContent = 'Upload complete!';
                status.className = 'status success';
                return;
            }
            
            const start = currentChunk * chunkSize;
            const end = Math.min(start + chunkSize, currentFile.size);
            const chunk = currentFile.slice(start, end);
            
            const formData = new FormData();
            formData.append('file', chunk);
            formData.append('chunk', currentChunk);
            formData.append('chunks', totalChunks);
            formData.append('name', currentFile.name);
            formData.append('session_id', '1'); // Default session ID for testing
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            status.textContent = `Uploading chunk ${currentChunk + 1} of ${totalChunks}...`;
            
            fetch('/simple-chunked-upload', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                
                currentChunk++;
                const progress = (currentChunk / totalChunks) * 100;
                progressFill.style.width = progress + '%';
                
                if (data.success) {
                    status.textContent = 'Upload completed successfully!';
                    status.className = 'status success';
                    result.innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
                } else {
                    uploadChunk(); // Continue with next chunk
                }
            })
            .catch(error => {
                status.textContent = 'Upload failed: ' + error.message;
                status.className = 'status error';
                console.error('Upload error:', error);
            });
        }
        
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    </script>
</body>
</html>
