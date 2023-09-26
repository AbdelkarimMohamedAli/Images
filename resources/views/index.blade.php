
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-header">Upload Image</div>

                <form method="post" action="{{url('/upload')}}"  enctype="multipart/form-data">
                    @csrf
                    <input type="file" id="files" name="files[]" multiple><br><br>                    <button type="submit">Upload Image</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
