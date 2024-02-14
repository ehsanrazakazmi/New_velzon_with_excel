@extends('layouts.master')
@section('title')
    @lang('translation.role')
@endsection
@section('css')
    <!--datatable css-->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <!--datatable responsive css-->
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <!-- Select2 css-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Tables
        @endslot
        @slot('title')
            Roles
        @endslot
    @endcomponent

    <!-- Session messeges -->
    @include('partials.session')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0"><strong>Roles</strong></h4>
                    <div class="col-sm-auto">
                        <div>
                            @can('Role create')
                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                    id="create-btn" data-bs-target="#showModal">
                                    Add Role
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>

                {{-- This data-table starts here... --}}
                <div class="card-body">
                    @can('Role list')   
                        <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" data-sort="customer_name">Sr.</th>
                                    <th class="text-center" data-sort="email">Name</th>
                                    <th class="text-center" data-sort="email">Desciption</th>
                                    <th class="text-center" data-sort="email">Permissions</th>
                                    <th class="text-center" data-sort="action">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $key => $role)
                                    <tr>
                                        <td class="text-center">{{ $key+1 }}</td>
                                        <td class="text-center" data-search="{{$role->name}}">{{$role->name}}</td>
                                        <td class="text-center" data-search="{{$role->description}}">{{$role->description}}</td>
                                        <td class="text-center">
                                            
                                            <button data-bs-toggle="modal" data-bs-target="#myModal{{$key}}" class="btn btn-link">
                                                <i class="ri-eye-line"></i>
                                            </button>
                                            <div id="myModal{{$key}}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="myModalLabel">Permissions</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div style="max-height: 300px; overflow-y: auto;">
                                                                @foreach ($role->permissions as $item)
                                                                    <p>{{$item->name}}</p>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div>
                                            <!-- /.modal -->
                                        </td>

                                        <td>
                                            @if($role->name != 'Super Admin')
                                                <div class="d-flex gap-2 justify-content-center">
                                                    @can('Role edit')
                                                        <div class="edit">
                                                            <button class="btn btn-sm btn-success"><a href="{{ route('role.edit', encrypt($role->id)) }}" class="text-white"><i class="ri-edit-line"></i></a></button>
                                                        </div>
                                                    @endcan
                                                    @can('Role delete') 
                                                        <div class="remove">
                                                            <button class="btn btn-sm btn-danger remove-item-btn" data-bs-toggle="modal" data-bs-target="#deleteRecordModal{{$key}}"><i class="ri-delete-bin-5-line"></i></button>
                                                        </div>
                                                    @endcan
                                                </div>

                                                <!-- Modal -->
                                                <div class="modal fade zoomIn" id="deleteRecordModal{{$key}}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                                                id="btn-close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mt-2 text-center">
                                                                    <script src="https://cdn.lordicon.com/lordicon-1.4.1.js"></script>
                                                                    <lord-icon
                                                                        src="https://cdn.lordicon.com/wpyrrmcq.json"
                                                                        trigger="hover"
                                                                        style="width:250px;height:250px">
                                                                    </lord-icon>
                                                                <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                                                    <h4>Are you Sure ?</h4>
                                                                    <p class="text-muted mx-4 mb-0">Are you Sure You want to Remove this Record ?</p>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                                <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>

                                                                <form method="post" action="{{ route('role.distroy', ['id' => encrypt($role->id)]) }}" method="POST">
                                                                    @method('DELETE')
                                                                    @csrf
                                                                    <button type="submit" class="btn w-sm btn-danger" id="delete-record">Yes, Delete It!</button>
                                                                </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end modal -->
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endcan
                    
                </div><!-- end card-body -->

            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->

    {{-- create roles modal --}}
    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Add Role Here...</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
               
                <form method="POST" action="{{ route('role.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" placeholder="Name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" placeholder="Description" class="form-control" required></textarea>
                        </div>
                        <div>
                            <label for="permissions" class="form-label">Permissions</label>
                            <select class="js-example-basic-multiple" name="permission[]" multiple="multiple" required>
                                @foreach($permission as $value)
                                    <option value="{{ $value->name }}">{{ $value->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            @can('Role create')
                            <button type="submit" class="btn btn-success" id="add-btn">Add Role</button>
                            @endcan
                        </div>
                    </div>
                </form>   
            </div>
        </div>
    </div>

@endsection
    
@section('script')
    <!--jquery cdn-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!--select2 cdn-->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ URL::asset('assets/js/pages/select2.init.js') }}"></script>

    <!-- Datatable CDN -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="{{ URL::asset('assets/js/pages/datatables.init.js') }}"></script>

    <!-- App JS -->
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
