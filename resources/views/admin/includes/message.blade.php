@if($message = Session::get('success'))
    <script>
        $.notify("{{$message}}", {type:"success"})
    </script>
@endif
@if($message = Session::get('error'))
    <script>
        $.notify("{{$message}}", {type:"danger"})
    </script>
@endif