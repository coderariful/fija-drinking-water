@props(['itemId','value','model','method', 'inputType', 'inputStyle'])

@php
    $inputType = $inputType??'text';
    $inputStyle = $inputStyle??[];
    $inputStyle = [
        'width:110px' => $inputType=='date',
        'width:50px' => $inputType=='number',
        'width:70px' => !in_array($inputType, ['date', 'number']),
        'height:auto!important',
        'line-height:initial!important',
        'font-size:14px',
        'padding:0'
    ] + $inputStyle;
    $inputClass = [
        'text-center' => $inputType=='number',
        'number-input' => $inputType=='number',
        'form-control-sm',
        'border',
        'py-0',
    ];
@endphp

<div {{$attributes}} x-data="{edit: false}">
    <span class="pl-2" x-show="!edit">
        {{$value}}
        @if(auth()->user()->isAdmin())
        <a href="javascript:void(0)" @click="edit=true">
            <i class="fa fa-pencil text-secondary ml-2" wire:loading.attr="hidden" wire:target="{{$method}}({{$itemId}})"></i>
            <span class="fa fa-spinner fa-spin" wire:loading wire:target="{{$method}}({{$itemId}})"></span>
        </a>
        @endif
    </span>
    @if(auth()->user()->isAdmin())
    <span class="form-group my-0 justify-content-center" x-bind:class="{'d-flex': edit}" x-show="edit" x-on:entryUpdated="edit=false">
        <input type="{{$inputType}}" @class($inputClass) wire:model="{{$model}}.{{$itemId}}" @style($inputStyle) @if($inputType=='number') min="0" @endif>
        <button wire:click="{{$method}}({{$itemId}})" x-on:click.debounce="edit=false" class="border-0 text-white bg-success">
            <i class="fa fa-check" wire:loading.attr="hidden" wire:target="{{$method}}({{$itemId}})"></i>
            <span class="fa fa-spinner fa-spin" wire:loading wire:target="{{$method}}({{$itemId}})"></span>
        </button>
        <button x-on:click="edit=false" class="border-0 text-white bg-danger"><i class="fa fa-times"></i></button>
    </span>
    @endif
</div>
