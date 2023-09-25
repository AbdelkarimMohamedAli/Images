@extends('layouts.app')

@section('content')
<div class="container col-sm-12">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">

                    <ul class="list-inline">
                        <!-- <li class="list-inline-item">RegisterStatus</li> -->
                        <!-- @foreach($statuses as $statusess)
                        <li class="list-inline-item"><a href="{{ route('status', ['slug' => $statusess] ) }}" class="card-header">{{ $statusess }}</a></li>
                        @endforeach -->
                    <li class="list-inline-item"><a href="{{ url('/register/pending') }}" class="card-header" @if($slug == 'pending') style="background: #3490dc !important;color: #fff !important;" @endif>Pending</a></li>
                    <li class="list-inline-item"><a href="{{ url('/register/rejected') }}" class="card-header" @if($slug == 'rejected') style="background: #3490dc !important;color: #fff !important;" @endif>Rejected</a></li>
                    <li class="list-inline-item"><a href="{{ url('/register/approved') }}"class="card-header"  @if($slug == 'approved')  style="background: #3490dc !important;color: #fff !important;" @endif>Approved</a></li>
                        
                    </ul>

                </div>

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
                        <th scope="col">Name</th>
                        <th scope="col">Address</th>
                        <th scope="col">Company</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Nationalid</th>
                        <th scope="col">Job</th>
                        <th scope="col">Nationality</th>
                        <th scope="col">Email</th>
                        <th scope="col">Industrialsector</th>
                        <th scope="col">Status</th>
                        <th scope="col">AdminStatus</th>

                      
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($personals as $personal)
                    <tr>
                        <td>{{ $personal->id }}</td>
                        <td>{{ $personal->name }}</td>
                        <td>{{ $personal->adress }}</td>
                        <td>{{ $personal->company }}</td>
                        <td>{{ $personal->phone }}</td>
                        <td>{{ $personal->nationalid }}</td>
                        <td>{{ $personal->job }}</td>
                        <td>{{ $personal->nationality }}</td>
                        <td>{{ $personal->email }}</td>
                        <td>{{ $personal->industrialsector }}</td>
                        <td>{{ $personal->status }}</td>                        
                        <td>                                                                                                        
                        <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" action="{{ route('registerstatus', ['id' => $personal->id]) }}" method="post" role="search">
                            {{csrf_field()}}
                            <select name="status" class="form-control">        
                                <option value="pending" <?php if($slug == 'pending'){ echo "selected"; } ?>>Pending</option>
                                <option value="rejected" <?php if($slug == 'rejected') { echo "selected"; } ?>>Rejected</option>
                                <option value="approved" <?php if($slug == 'approved') { echo "selected"; } ?>>Approved</option>
                            </select>
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search fa-sm">submit</i>
                            </button>
                        </form>
                        </td> 
                        
                    </tr>
                    @endforeach
                        
                    </tbody>
                    </table>

                    {{ $personals->links() }}
                </div>
            </div>
            <button type="button" class="btn btn-dark mt-3">
                @if($slug=='pending')
                <a href="{{ url('/pending/export') }}">Export</a>
                @elseif($slug=='approved')
                <a href="{{ url('/approval/export') }}">Export</a>
                @elseif($slug=='rejected')
                <a href="{{ url('/rejected/export') }}">Export</a>
                @endif

            </button>
        </div>
    </div>
</div>
@endsection
