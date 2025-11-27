<div>
    <div class="row">
        <div class="col">
            <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                <a class="breadcrumb-item text-white" href="{{ route('user.dashboard') }}">{{__('Home')}}</a>
                <a class="breadcrumb-item text-white"
                    href="{{ route('admin.sms-template') }}">{{__('SMS Templates')}}</a>
                <span class="breadcrumb-item active">{{$title}}</span>
                <span class="breadcrumb-info" id="time"></span>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card card-dark bg-dark">
                <div class="card-header">
                    <h6 class="card-title">{{$title}}</h6>
                </div>
                <form method="POST" wire:submit="submit">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label for="name"
                                        class="card-title font-weight-bold">{{__('Template Name:')}}</label>
                                    <input type="text" id="name" class="form-control"
                                        placeholder="{{__('Template Name')}}" wire:model="name">
                                    @error ('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="my-4" x-data="$store.templateParameter" wire:ignore>
                                    <strong>Parameters:</strong>
                                    @foreach($template->params as $key => $value)
                                        <p class="mb-0">
                                            <code x-on:click="copy('{{ $key }}')" style="cursor: pointer;"
                                                title="Click to copy" data-toggle="tooltip">{{$key}}</code> : {{$value}}
                                            <span x-show="copied === '{{ $key }}'" x-transition
                                                class="text-success text-xs ml-2">Copied!</span>
                                        </p>
                                    @endforeach
                                </div>

                                <div class="form-group">
                                    <label for="body"
                                        class="card-title font-weight-bold">{{__('Template Body:')}}</label>
                                    <textarea name="body" id="body" class="form-control"
                                        placeholder="{{__('Template Body')}}" wire:model="body"></textarea>
                                    @error ('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        <div class="card-footer">
                            <button class="btn btn-wave-light btn-danger btn-lg" type="submit">{{__('Submit')}}</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('templateParameter', {
                copied: false,
                copy(text) {
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(text).then(() => {
                            this.copied = text;
                            setTimeout(() => this.copied = false, 2000);
                        }).catch(err => {
                            console.error('Failed to copy: ', err);
                            this.fallbackCopy(text);
                        });
                    } else {
                        this.fallbackCopy(text);
                    }
                },
                fallbackCopy(text) {
                    var textArea = document.createElement('textarea');
                    textArea.value = text;

                    // Avoid scrolling to bottom
                    textArea.style.top = '0';
                    textArea.style.left = '0';
                    textArea.style.position = 'fixed';

                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();

                    try {
                        var successful = document.execCommand('copy');
                        if (successful) {
                            this.copied = text;
                            setTimeout(() => this.copied = false, 2000);
                        } else {
                            console.error('Fallback: Copying text command was unsuccessful');
                        }
                    } catch (err) {
                        console.error('Fallback: Oops, unable to copy', err);
                    }

                    document.body.removeChild(textArea);
                }
            })
        });
    </script>
@endpush