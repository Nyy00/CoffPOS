@props(['label' => '', 'name' => '', 'accept' => 'image/*', 'required' => false, 'disabled' => false, 'error' => '', 'preview' => true, 'currentImage' => ''])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
        <div class="space-y-1 text-center">
            @if($preview)
                <div id="{{ $name }}_preview" class="mb-4 {{ $currentImage ? '' : 'hidden' }}">
                    @if($currentImage)
                        <img src="{{ $currentImage }}" alt="Current image" class="mx-auto h-32 w-32 object-cover rounded-lg">
                        <p class="mt-2 text-sm text-gray-500">Current image</p>
                    @endif
                </div>
            @endif
            
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div class="flex text-sm text-gray-600">
                <label for="{{ $name }}" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                    <span>Upload a file</span>
                    <input 
                        id="{{ $name }}" 
                        name="{{ $name }}" 
                        type="file" 
                        accept="{{ $accept }}"
                        {{ $required ? 'required' : '' }}
                        {{ $disabled ? 'disabled' : '' }}
                        class="sr-only"
                        onchange="previewImage(this, '{{ $name }}_preview')"
                        {{ $attributes }}
                    >
                </label>
                <p class="pl-1">or drag and drop</p>
            </div>
            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
        </div>
    </div>
    
    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @elseif($errors->has($name))
        <p class="mt-1 text-sm text-red-600">{{ $errors->first($name) }}</p>
    @endif
</div>

@if($preview)
<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const file = input.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <img src="${e.target.result}" alt="Preview" class="mx-auto h-32 w-32 object-cover rounded-lg">
                <p class="mt-2 text-sm text-gray-500">New image preview</p>
            `;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endif