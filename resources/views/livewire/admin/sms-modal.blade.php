<div x-data="{show:false, message:'', max_length: 160, utf_length: 70, success: false}">
    <div class="mb-3 px-4">
        @if($customer)
            <div class="border pt-2">
                <table class="table text-center mb-2">
                    <tr>
                        <th class="p-0">Customer Name</th>
                        <th class="p-0">Customer Phone</th>
                    </tr>
                    <tr>
                        <td class="p-0">{{$customer->name}}</td>
                        <td class="p-0">{{$customer->phone}}</td>
                    </tr>
                </table>
            </div>
        @endif
    </div>
    <div class="text-center">
        <button class="btn btn-primary" wire:click="send('customer-daily-sms')">Send Daily SMS</button>
        <button class="btn btn-primary" wire:click="send('customer-monthly-sms')">Send Monthly SMS</button>
        <button class="btn btn-primary" x-on:click="show=!show" x-bind:class="show?'disabled':''">Send Custom SMS</button>
    </div>
    <div x-show="show" class="mt-4 mb-2 px-3">
        <label for="sms_message">Message</label>
        <textarea class="form-control" id="sms_message" placeholder="Write the message" x-model="message" wire:model.defer="message" required></textarea>
        <div class="d-flex justify-content-between">
            <div class="text-left">
                @error('message')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
            <div class="text-right">
                Characters <span x-text="message.length"></span> | SMS Length <span x-text="Math.ceil(message.length/utf_length)"></span>
            </div>
        </div>
        <button class="btn btn-success mt-1" type="button" wire:click="send" x-on:click="message=null">Send</button>
        <button class="btn btn-secondary mt-1" type="button" @click="show=!show">Cancel</button>
    </div>
    @if($alert)
    <div class="alert alert-{{$alert['type']}} mt-3 mx-3">
        {{$alert['message']}}
    </div>
    @endif
</div>
