<div 
    x-data="{ message: '', type: '', visible: false, timeout: null }"
    x-on:flash.window="message = $event.detail.message; type = $event.detail.type ?? 'success'; visible = true; clearTimeout(timeout); timeout = setTimeout(() => visible = false, 3000)"
    x-show="visible"
    x-transition
    class="fixed top-4 right-4 px-4 py-2 rounded-sm shadow-sm text-white z-50"
    :class="{
        'bg-green-500': type === 'success',
        'bg-red-500': type === 'error',
        'bg-yellow-500': type === 'warning'
    }"
    style="display: none;"
>
    <span x-text="message"></span>
</div>