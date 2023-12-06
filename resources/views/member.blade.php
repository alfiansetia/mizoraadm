@extends('layouts.main')
@section('title', 'Users')
@section('content')
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('plugins/DataTables/datatables.min.css') }}">
    @endpush


    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-users bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Member') }}</h5>
                            <span>{{ __('List of Member') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">{{ __('Users') }}</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- start message area-->
            @include('include.message')
            <!-- end message area-->
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="card-header">
                        <h3>{{ __('Users') }}</h3>
                    </div>
                    <div class="card-body">
                        <table id="user_table" class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Addres') }}</th>
                                    <th>{{ __('Total Point') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                                @foreach ($data as $d)
                                    <tr>
                                        <td>{{ $d->name }}</td>
                                        <td>{{ $d->email }}</td>
                                        <td>{{ $d->phone }}</td>
                                        <td>{{ $d->address }}</td>
                                        <td>{{ $d->total_point }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="#" id="{{ $d->id }}"
                                                    data-url="{{ url('member-detail/' . $d->id) }}"
                                                    class="btn btn-warning detailMember" data-toggle="modal"
                                                    data-target="#detailMember">Detail</a>
                                                <form action="member/{{ $d->id }}" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <input type="submit" class="btn btn-danger" value="Delete">
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="detailMember" tabindex="-1" aria-labelledby="exampleModalLabel" data-backdrop="static"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detail Member</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="#" method="POST">
                        <div class="modal-body">
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" required autocomplete="off">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="text" class="form-control" id="email" required autocomplete="off">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" id="phone" required autocomplete="off">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address" required autocomplete="off">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Level Point</label>
                                    <input type="text" class="form-control" id="level_point" required autocomplete="off">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Toal Point</label>
                                    <input type="text" class="form-control" id="total_point" required autocomplete="off">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Active Until</label>
                                    <input type="text" class="form-control" id="active_until" required
                                        autocomplete="off">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Birth Date</label>
                                    <input type="text" class="form-control" id="birth_date" required
                                        autocomplete="off">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Gender</label>
                                    <input type="text" class="form-control" id="gender" required
                                        autocomplete="off">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" required
                                        autocomplete="off">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Distric</label>
                                    <input type="text" class="form-control" id="distric" required
                                        autocomplete="off">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Villages</label>
                                    <input type="text" class="form-control" id="villages" required
                                        autocomplete="off">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Postal</label>
                                    <input type="text" class="form-control" id="postal" required
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- push external js -->
    @push('script')
        <script src="{{ asset('js/form-components.js') }}"></script>
        {{-- <script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script> --}}
        {{-- <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script> --}}
        <!--server side users table script-->
        <!-- <script src="{{ asset('js/custom.js') }}"></script> -->
    @endpush
    @push('script')
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script>
            $(document).on('click', '.detailMember', function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
                let url = $(this).data('url');
                $.ajax({
                    url: url,
                    method: 'get',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#name').val(response.name)
                        $('#email').val(response.email)
                        $('#phone').val(response.phone)
                        $('#address').val(response.address)
                        $('#level_point').val(response.level_point_id)
                        $('#total_point').val(response.total_point)
                        $('#active_until').val(response.active_until)
                        $('#birth_date').val(response.birth_date)
                        $('#gender').val(response.gender)
                        $('#city').val(response.city_id)
                        $('#distric').val(response.districts_id)
                        $('#villages').val(response.villages_id)
                        $('#postal').val(response.postal)
                    }
                });
            });
        </script>
    @endpush
@endsection
