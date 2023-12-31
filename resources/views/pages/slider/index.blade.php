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
                            <h5>{{ __('Slider') }}</h5>
                            <span>{{ __('Lists of Slider') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">{{ __('Slider') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Slider') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-block">
                        <div class="flex-row d-flex">
                            <div>
                                <h3 class="pt-2">Table of Pages</h3>
                            </div>
                            <a href="{{ route('slider.create') }}"
                                class="ms-auto btn btn-primary btn-rounded-sm ms-auto">Add
                                Slider</a>
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
                                        <th>Name</th>
                                        <th>Url</th>
                                        <th>Image</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datas as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->url }}</td>
                                            <td>
                                                <img src="{{ asset('sliders/' . $item->image) }}" alt=""
                                                    width="100px">
                                            </td>
                                            <td>
                                                <a href="{{ route('slider.edit', $item->id) }}"><i
                                                        class='ik ik-edit f-16 mr-15 text-green'></i></a>
                                                <a href="javascript:void(0);" onclick="confirmDelete()">
                                                    <i class="ik ik-trash-2 f-16 text-red"></i>
                                                </a>
                                                <form id="delete-slider" action="{{ route('slider.destroy', $item->id) }}"
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

        function confirmDelete() {
            if (confirm("Are you sure you want to delete this Slider?")) {
                document.getElementById('delete-slider').submit();
            }
        }
    </script>
@endsection
