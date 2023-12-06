@extends('layouts.main')
@section('content')
    <style>
        #table-data {
            width: 100%;
            min-width: 100%;
        }
    </style>

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i id="icon_title" class="ik ik-star bg-yellow"></i>
                        <div class="d-inline">
                            <h5>{{ __('Pages') }}</h5>
                            <span>{{ __('Lists of Pages') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">{{ __('Reward') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Pages') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-block">
                        <div class="flex-row d-flex ms-auto">
                            <div>
                                <h3 class="pt-2">Table of Pages</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>{{ session()->get('success') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <div class="container align-item-center">
                            <table id="table-data" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Whatsapp</th>
                                        <th>Address</th>
                                        <th>Instagram</th>
                                        <th>Youtube</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($settings as $setting)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $setting->email }}</td>
                                            <td>{{ $setting->phone }}</td>
                                            <td>{{ $setting->whatsapp }}</td>
                                            <td>{{ $setting->address }}</td>
                                            <td>{{ $setting->ig }}</td>
                                            <td>{{ $setting->youtube }}</td>
                                            <td>
                                                <a href="#" id="{{ $setting->id }}" class="editSetting"
                                                    data-url="{{ route('setting.edit', $setting->id) }}"
                                                    data-toggle="modal" data-target="#editSetting">
                                                    <i class='ik ik-edit f-16 mr-15 text-green'></i>
                                                </a>
                                                <a href="javascript:void(0);" onclick="confirmDelete()">
                                                    <i class="ik ik-trash-2 f-16 text-red"></i>
                                                </a>
                                                <form id="delete-page"
                                                    action="{{ route('setting.destroy', $setting->id) }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modal edit --}}

    <div class="modal fade" id="editSetting" tabindex="-1" aria-labelledby="exampleModalLabel" data-backdrop="static"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Setting</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="POST" id="edit_setting_form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="text" name="email" class="form-control" id="email" required
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Phone</label>
                            <div class="col-sm-9">
                                <input type="text" name="phone" class="form-control" id="phone" required
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">WhatsApp</label>
                            <div class="col-sm-9">
                                <input type="text" name="whatsapp" class="form-control" id="whatsapp" required
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Address</label>
                            <div class="col-sm-9">
                                <input type="text" name="address" class="form-control" id="address" required
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Instagram</label>
                            <div class="col-sm-9">
                                <input type="text" name="ig" class="form-control" id="ig" required
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Youtube</label>
                            <div class="col-sm-9">
                                <input type="text" name="youtube" class="form-control" id="youtube" required
                                    autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="edit_setting_btn" class="btn btn-success">Update Setting</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- akhir modal edit --}}

    @push('script')
        <script src="{{ asset('js/form-components.js') }}"></script>
        <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
        <script src="{{ asset('plugins/DataTables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('js/datatables.js') }}"></script>
    @endpush
    <script>
        $(document).ready(function() {
            $('#table-data').DataTable();
        });

        $(document).on('click', '.editSetting', function(e) {
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
                    console.log(response);
                    $('#email').val(response.email)
                    $('#phone').val(response.phone)
                    $('#address').val(response.address)
                    $('#ig').val(response.ig)
                    $('#whatsapp').val(response.whatsapp)
                    $('#youtube').val(response.youtube)
                    $('#id').val(response.id)
                }
            });
        });

        $("#edit_setting_form").submit(function(e) {
            e.preventDefault();
            const fd = new FormData(this);
            const id = $('#id').val();
            const url = '{{ route('setting.update', '') }}/' + id;
            $("#edit_setting_btn").text('Updating...');
            $.ajax({
                url: url,
                method: 'POST',
                data: fd,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status == 200) {
                        window.location.reload();
                    }
                    $("#edit_setting_btn").text('Update Setting');
                    $("#edit_setting_form")[0].reset();
                    $("#editSetting").modal('hide');
                }
            });
        });

        function confirmDelete() {
            if (confirm("Are you sure you want to delete this Pages?")) {
                document.getElementById('delete-page').submit();
            }
        }
    </script>
@endsection
