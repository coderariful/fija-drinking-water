@props(['middle' => false, 'bgLight' => false])
<?php
    $style = $middle ? 'top: 50%; left: 50%; transform: translate(-50%, -50%);' : 'top: 0; left: 0;';
    $style .= $bgLight ? 'background-color: rgba(255, 255, 255, 0.6);' : 'background: rgba(0, 0, 0, .5);';
?>
<div class="position-absolute w-100 h-100 align-items-center {{ !$middle ? 'pt-5' : '' }}" wire:loading wire:loading.class="d-flex"
     style="z-index: 99999; {{$style}} backdrop-filter: blur(2px)">
    <div class="py-4 text-center bg-light-blue {{ !$middle ? 'my-5' : '' }} w-100">
        <h3 class="text-white mb-0">Processing</h3>
    </div>
</div>
