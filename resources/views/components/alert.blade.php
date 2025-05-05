<div>
    @if($message)
        <div class="alert alert-{{ $class }} alert-dismissible fade show" role="alert">
            <strong>{{ $type ? ucfirst($type) : 'Info' }}!</strong> <br>
            @if(is_array($message))
                @foreach ($message as $item)
                    {{ $item }} <br>
                @endforeach
            @else
                {{ $message }}
            @endif
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            </button>
        </div>
    @elseif ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> <br>
            @foreach ($errors->all() as $error)
                {{ $error }} <br>
            @endforeach
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
</div>