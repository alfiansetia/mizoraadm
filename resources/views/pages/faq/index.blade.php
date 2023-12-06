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
                            <h5>{{ __('Faq') }}</h5>
                            <span>{{ __('Lists of Faq') }}</span>
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
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Faq') }}</li>
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
                            <a href="#" class="btn btn-primary btn-rounded-sm" data-toggle="modal"
                                data-target="#tambahFaq">Add Faq</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-dismissible fade show hide" role="alert">
                            <strong id="pesan"></strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="container align-item-center">
                            <table id="table-data" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Question</th>
                                        <th>Answer</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($faqs as $faq)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $faq->question }}</td>
                                            <td>{{ $faq->answer }}</td>
                                            <td>
                                                <a href="#" id="{{ $faq->id }}" class="editFaq"
                                                    data-url="{{ route('faq.edit', $faq->id) }}" data-toggle="modal"
                                                    data-target="#editFaq">
                                                    <i class='ik ik-edit f-16 mr-15 text-green'></i>
                                                </a>
                                                <a href="javascript:void(0);" onclick="confirmDelete()">
                                                    <i class="ik ik-trash-2 f-16 text-red"></i>
                                                </a>
                                                <form id="delete-page" action="{{ route('faq.destroy', $faq->id) }}"
                                                    method="POST" style="display: none;">
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

    {{-- modal tambah --}}
    <div class="modal fade" id="tambahFaq" tabindex="-1" aria-labelledby="exampleModalLabel" data-backdrop="static"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Faq</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="POST" id="faq_form">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Question</label>
                            <div class="col-sm-9">
                                <textarea name="question" class="form-control" cols="10"></textarea>
                                @error('question')
                                    <span>{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Answer</label>
                            <div class="col-sm-9">
                                <input type="text" name="answer" class="form-control" autocomplete="off">
                                @error('answer')
                                    <span>{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="faq_btn" class="btn btn-success">Tambah Faq</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- akhir modal tambah --}}

    {{-- modal edit --}}
    <div class="modal fade" id="editFaq" tabindex="-1" aria-labelledby="exampleModalLabel" data-backdrop="static"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Faq</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="POST" id="edit_faq_form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Question</label>
                            <div class="col-sm-9">
                                <textarea name="question" id="question" cols="10" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Answer</label>
                            <div class="col-sm-9">
                                <input type="text" name="answer" id="answer" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="edit_faq_btn" class="btn btn-success">Update Faq</button>
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

            $("#faq_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                $("#faq_btn").text('Adding...');
                $.ajax({
                    url: '{{ route('faq.store') }}',
                    method: 'post',
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 200) {
                            window.location.reload()
                            $("#faq_btn").text('Tambah Faq');
                            $("#tambahFaq").modal('hide');
                            $('.alert').removeClass('alert-danger').addClass('alert-success')
                                .removeClass('hide');
                            $('#pesan').text('Sukses menambah data Faq');
                        }

                    },
                    error: function(xhr, status, error) {
                        var errors = xhr.responseJSON.errors;
                        if (errors) {
                            displayValidationErrors(errors);
                        } else {
                            console.log(xhr.responseText);
                        }
                        $("#faq_btn").text('Tambah Faq');
                    }
                });
            });

            $(document).on('click', '.editFaq', function(e) {
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
                        $('#id').val(response.id)
                        $('#answer').val(response.answer)
                        $('#question').val(response.question)
                    }
                });
            });

            $("#edit_faq_form").submit(function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                const id = $('#id').val();
                const url = '{{ route('faq.update', '') }}/' + id;
                $("#edit_faq_btn").text('Updating...');
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
                            $("#editSetting").modal('hide');
                            $("#edit_setting_btn").text('Update Faq');
                            $('.alert').removeClass('alert-danger').addClass('alert-success')
                                .removeClass('hide');
                            $('#pesan').text('Sukses update data Faq');
                        }
                    }
                });
            });

            function displayValidationErrors(errors) {
                $(".error-message").remove();
                $.each(errors, function(field, messages) {
                    var $input = $('[name="' + field + '"]');
                    $input.after('<span class="error-message text-danger">' + messages.join(', ') +
                        '</span>');
                });
            }

            function confirmDelete() {
                if (confirm("Are you sure you want to delete this Faq?")) {
                    document.getElementById('delete-page').submit();
                }
            }
        });
    </script>
@endsection
