@extends('layouts.app')

@section('content')
<div class="container col-sm-12">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Subscribes</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table">
                    <thead>
                        <tr>
                        <th scope="col">id</th>
                        <th scope="col">name</th>
                        <th scope="col">email</th>
                        <th scope="col">Companyname</th>
                        <th scope="col">Phonecompany</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Job</th>
                        <th scope="col">Industrialsector</th>
                      
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($subscribes as $subscribe)
                    <tr>
                        <td>{{ $subscribe->id }}</td>
                        <td>{{ $subscribe->name }}</td>
                        <td>{{ $subscribe->email }}</td>
                        <td>{{ $subscribe->companyname }}</td>
                        <td>{{ $subscribe->phonecompany }}</td>
                        <td>{{ $subscribe->phone }}</td>
                        <td>{{ $subscribe->job }}</td>
                        <td>{{ $subscribe->industrialsector }}</td>
                        
                        
                       
                        

                    </tr>
                    @endforeach
                        
                    </tbody>
                    </table>

                    {{ $subscribes->links() }}
                </div>
            </div>
            <button type="button" class="btn btn-dark mt-3">

                <a href="{{ url('/subscribes/export') }}">Export</a>
            </button>
        </div>
    </div>
</div>
@endsection
