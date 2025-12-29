<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🚂 Railway Production Debug</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background: #f5f5f5;
            line-height: 1.6;
        }
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            background: white; 
            border-radius: 8px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            color: white; 
            padding: 30px; 
            text-align: center; 
        }
        .header h1 { margin: 0; font-size: 2.5em; }
        .header p { margin: 10px 0 0 0; opacity: 0.9; }
        .content { padding: 30px; }
        .success { color: #27ae60; font-weight: bold; }
        .error { color: #e74c3c; font-weight: bold; }
        .warning { color: #f39c12; font-weight: bold; }
        .info { color: #3498db; font-weight: bold; }
        .section { 
            margin: 30px 0; 
            padding: 25px; 
            border: 1px solid #e1e8ed; 
            border-radius: 8px; 
            background: #fafafa;
        }
        .section h2 { 
            margin: 0 0 20px 0; 
            color: #2c3e50; 
            border-bottom: 2px solid #3498db; 
            padding-bottom: 10px;
        }
        .grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
            gap: 20px; 
        }
        .card { 
            background: white; 
            padding: 20px; 
            border-radius: 6px; 
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .card h3 { margin: 0 0 15px 0; color: #34495e; }
        .status-badge { 
            display: inline-block; 
            padding: 4px 12px; 
            border-radius: 20px; 
            font-size: 0.85em; 
            font-weight: bold; 
            text-transform: uppercase;
        }
        .status-success { background: #d4edda; color: #155724; }
        .status-error { background: #f8d7da; color: #721c24; }
        .status-warning { background: #fff3cd; color: #856404; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; font-weight: 600; }
        .upload-test { 
            background: #e8f4fd; 
            border: 2px dashed #3498db; 
            padding: 30px; 
            text-align: center; 
            border-radius: 8px; 
            margin: 20px 0;
        }
        .upload-test input[type="file"] { 
            margin: 15px 0; 
            padding: 10px; 
            border: 1px solid #ddd; 
            border-radius: 4px;
        }
        .upload-test button { 
            background: #3498db; 
            color: white; 
            border: none; 
            padding: 12px 24px; 
            border-radius: 4px; 
            cursor: pointer; 
            font-size: 16px;
        }
        .upload-test button:hover { background: #2980b9; }
        .alert { 
            padding: 15px; 
            margin: 20px 0; 
            border-radius: 4px; 
            border-left: 4px solid;
        }
        .alert-danger { background: #f8d7da; border-color: #dc3545; color: #721c24; }
        .alert-warning { background: #fff3cd; border-color: #ffc107; color: #856404; }
        .alert-info { background: #d1ecf1; border-color: #17a2b8; color: #0c5460; }
        pre { 
            background: #f8f9fa; 
            padding: 15px; 
            border-radius: 4px; 
            overflow-x: auto; 
            font-size: 0.9em;
        }
        .timestamp { 
            text-align: center; 
            color: #6c757d; 
            font-style: italic; 
            margin-top: 30px; 
            padding-top: 20px; 
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚂 Railway Production Debug</h1>
            <p>Comprehensive system diagnostics for CoffPOS</p>
        </div>
        
        <div class="content">
            <div class="alert alert-warning">
                <strong>⚠️ Security Notice:</strong> This debug page should only be accessible in production for troubleshooting. 
                Remove or disable this route after debugging is complete.
            </div>

            <!-- Database Section -->
            <div class="section">
                <h2>🗄️ Database Connection</h2>
                <div class="card">
                    <h3>Connection Status</h3>
                    <span class="status-badge status-{{ $results['database']['status'] }}">
                        {{ $results['database']['status'] === 'success' ? '✅ Connected' : '❌ Failed' }}
                    </span>
                    <p><strong>Message:</strong> {{ $results['database']['message'] }}</p>
                    
                    @if(isset($results['database']['details']))
                        <table>
                            @foreach($results['database']['details'] as $key => $value)
                                <tr>
                                    <th>{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                                    <td>
                                        @if(is_array($value))
                                            {{ implode(', ', array_slice($value, 0, 5)) }}
                                            @if(count($value) > 5) ... ({{ count($value) }} total) @endif
                                        @else
                                            {{ $value }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    @endif
                </div>
            </div>

            <!-- Storage Section -->
            <div class="section">
                <h2>💾 Storage & File System</h2>
                
                <div class="grid">
                    <div class="card">
                        <h3>Directory Status</h3>
                        <table>
                            @foreach($results['storage']['directories'] as $name => $info)
                                <tr>
                                    <th>{{ $name }}</th>
                                    <td>
                                        @if($info['exists'])
                                            <span class="success">✅ EXISTS</span>
                                            @if($info['writable'])
                                                <span class="success">(WRITABLE)</span>
                                            @else
                                                <span class="error">(NOT WRITABLE)</span>
                                            @endif
                                            <br><small>Permissions: {{ $info['permissions'] }}</small>
                                        @else
                                            <span class="error">❌ NOT EXISTS</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>

                    <div class="card">
                        <h3>Symbolic Link</h3>
                        @if($results['storage']['symbolic_link']['exists'])
                            <p class="success">✅ Symbolic link exists</p>
                            <p><strong>Target:</strong> {{ $results['storage']['symbolic_link']['target'] }}</p>
                        @else
                            <p class="error">❌ Symbolic link missing</p>
                            <p><strong>Expected path:</strong> {{ $results['storage']['symbolic_link']['path'] }}</p>
                        @endif
                    </div>
                </div>

                <div class="grid">
                    <div class="card">
                        <h3>Write Test</h3>
                        <span class="status-badge status-{{ $results['storage']['write_test']['status'] }}">
                            {{ $results['storage']['write_test']['status'] === 'success' ? '✅ Success' : '❌ Failed' }}
                        </span>
                        <p>{{ $results['storage']['write_test']['message'] }}</p>
                    </div>

                    @if(isset($results['storage']['disk_space']))
                    <div class="card">
                        <h3>Disk Space</h3>
                        <p><strong>Free:</strong> {{ $results['storage']['disk_space']['free'] }}</p>
                        <p><strong>Total:</strong> {{ $results['storage']['disk_space']['total'] }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Environment Section -->
            <div class="section">
                <h2>🔧 Environment Variables</h2>
                <div class="card">
                    <table>
                        @foreach($results['environment'] as $key => $value)
                            <tr>
                                <th>{{ $key }}</th>
                                <td>{{ $value }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            <!-- PHP Section -->
            <div class="section">
                <h2>🐘 PHP Configuration</h2>
                <div class="card">
                    <table>
                        @foreach($results['php'] as $key => $value)
                            <tr>
                                <th>{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                                <td>{{ $value }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            <!-- Laravel Section -->
            <div class="section">
                <h2>🎯 Laravel Information</h2>
                <div class="card">
                    <table>
                        @foreach($results['laravel'] as $key => $value)
                            <tr>
                                <th>{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                                <td>
                                    @if(is_bool($value))
                                        <span class="{{ $value ? 'success' : 'warning' }}">
                                            {{ $value ? '✅ Yes' : '❌ No' }}
                                        </span>
                                    @else
                                        {{ $value }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            <!-- Upload Test Section -->
            <div class="section">
                <h2>📤 File Upload Test</h2>
                
                <div class="card">
                    <h3>Automated Test Result</h3>
                    <span class="status-badge status-{{ $results['upload_test']['status'] }}">
                        {{ $results['upload_test']['status'] === 'success' ? '✅ Success' : '❌ Failed' }}
                    </span>
                    <p>{{ $results['upload_test']['message'] }}</p>
                    
                    @if(isset($results['upload_test']['error']))
                        <div class="alert alert-danger">
                            <strong>Error:</strong> {{ $results['upload_test']['error'] }}
                        </div>
                    @endif
                </div>

                <div class="upload-test">
                    <h3>🧪 Manual Upload Test</h3>
                    <p>Test file upload functionality manually:</p>
                    <form id="uploadForm" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="test_file" accept="image/*" required>
                        <br>
                        <button type="submit">Test Upload</button>
                    </form>
                    <div id="uploadResult" style="margin-top: 20px;"></div>
                </div>
            </div>

            <div class="timestamp">
                <p>Generated at: {{ now()->format('Y-m-d H:i:s T') }}</p>
                <p><strong>⚠️ Remember to disable this debug route after troubleshooting!</strong></p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const resultDiv = document.getElementById('uploadResult');
            
            resultDiv.innerHTML = '<p class="info">🔄 Uploading...</p>';
            
            fetch('{{ route("debug.test-upload") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    resultDiv.innerHTML = `
                        <div class="alert alert-info">
                            <h4 class="success">✅ Upload Successful!</h4>
                            <p><strong>File:</strong> ${data.original_name}</p>
                            <p><strong>Size:</strong> ${data.size} bytes</p>
                            <p><strong>Path:</strong> ${data.path}</p>
                            <p><strong>URL:</strong> <a href="${data.url}" target="_blank">${data.url}</a></p>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="alert alert-danger">
                            <h4 class="error">❌ Upload Failed!</h4>
                            <p><strong>Error:</strong> ${data.message}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                resultDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <h4 class="error">❌ Request Failed!</h4>
                        <p><strong>Error:</strong> ${error.message}</p>
                    </div>
                `;
            });
        });
    </script>
</body>
</html>