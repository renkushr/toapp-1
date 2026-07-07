@php
    $variant = $variant ?? 'ci';
    $imageInputId = $imageInputId ?? 'chatImageInput';
    $fileInputId = $fileInputId ?? 'chatFileInput';
@endphp

<div class="chat-attach-actions chat-attach-actions--{{ $variant }}">
    <label class="chat-attach-btn chat-attach-btn--image" for="{{ $imageInputId }}" title="Attach photo">
        <i class="ti ti-photo"></i>
        <span>Photo</span>
    </label>
    <label class="chat-attach-btn chat-attach-btn--file" for="{{ $fileInputId }}" title="Attach file">
        <i class="ti ti-paperclip"></i>
        <span>File</span>
    </label>
    <input id="{{ $imageInputId }}" class="chat-attach-input" type="file" name="attachments[]" multiple accept="image/jpeg,image/png,image/gif,image/webp,image/*">
    <input id="{{ $fileInputId }}" class="chat-attach-input" type="file" name="attachments[]" multiple accept=".pdf,.doc,.docx,.txt,application/pdf,application/msword">
</div>
