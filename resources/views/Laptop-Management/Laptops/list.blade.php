@extends('layouts.master')
@section('title')
    @lang('translation.list-js')
@endsection
@section('css')
    <!--datatable css-->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <!--datatable responsive css-->
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Laptops
        @endslot
        @slot('title')
            List
        @endslot
    @endcomponent

    @include('partials.session')

    <!-- row starts -->
    <div class="row">

        <!-- Col starts -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0"><strong>Laptops</strong></h4>
                    <div class="col-sm-auto">
                        <div class="d-flex button-container">
                            @can('Laptop create')
                                <button type="button" class="btn btn-success add-btn" data-bs-toggle="modal" id="create-btn"
                                    data-bs-target="#showModal" style="margin-right: 10px;">
                                    <i class="ri-add-line align-bottom me-1"></i> Add
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <table id="example" class="table table-bordered dt-responsive nowrap table-striped align-middle"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Brand</th>
                                <th class="text-center">Model</th>
                                <th class="text-center">Serial No</th>
                                <th class="text-center">Processor</th>
                                <th class="text-center">RAM</th>
                                <th class="text-center">Storage</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Purchased</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Added by</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($laptops as $laptop)
                                <tr>
                                    <td class="text-center">{{ ++$i }}</td>
                                    <td class="text-center">{{ $laptop->brand }}</td>
                                    <td class="text-center">{{ $laptop->model }}</td>
                                    <td class="text-center">{{ $laptop->serial_number }}</td>
                                    <td class="text-center">{{ $laptop->processor ?: '-' }}</td>
                                    <td class="text-center">{{ $laptop->ram_gb ? $laptop->ram_gb . ' GB' : '-' }}</td>
                                    <td class="text-center">{{ $laptop->storage_gb ? $laptop->storage_gb . ' GB' : '-' }}</td>
                                    <td class="text-center">{{ $laptop->price }} {{ $laptop->currency }}</td>
                                    <td class="text-center">
                                        {{ $laptop->purchase_date ? \Carbon\Carbon::parse($laptop->purchase_date)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $badges = [
                                                'available' => 'bg-success',
                                                'assigned' => 'bg-info',
                                                'repair' => 'bg-warning',
                                                'retired' => 'bg-secondary',
                                            ];
                                            $badge = $badges[$laptop->status] ?? 'bg-light text-dark';
                                        @endphp
                                        <span class="badge {{ $badge }}">{{ ucfirst($laptop->status) }}</span>
                                    </td>
                                    <td class="text-center">{{ optional($laptop->user)->name ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <div class="edit">
                                                @can('Laptop edit')
                                                    <button class="btn btn-sm btn-success edit-item-btn"><a
                                                            href="{{ route('laptop.edit', encrypt($laptop->id)) }}"
                                                            class="text-white"><i class="ri-edit-line"></i></a></button>
                                                @endcan
                                            </div>
                                            <div class="remove">
                                                @can('Laptop delete')
                                                    <button class="btn btn-sm btn-danger remove-item-btn" data-bs-toggle="modal"
                                                        data-bs-target="#deleteRecordModal{{ $laptop->id }}"><i
                                                            class="ri-delete-bin-5-line"></i></button>
                                                @endcan
                                            </div>
                                        </div>

                                        <div class="modal fade zoomIn" id="deleteRecordModal{{ $laptop->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close" id="btn-close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mt-2 text-center">
                                                            <script src="https://cdn.lordicon.com/lordicon-1.4.1.js"></script>
                                                            <lord-icon src="https://cdn.lordicon.com/wpyrrmcq.json"
                                                                trigger="hover" style="width:250px;height:250px">
                                                            </lord-icon>
                                                            <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                                                <h4>Are you Sure ?</h4>
                                                                <p class="text-muted mx-4 mb-0">Are you Sure You want to
                                                                    Remove this Record ?</p>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                            <button type="button" class="btn w-sm btn-light"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <a href="{{ url('/laptop/delete/' . encrypt($laptop->id)) }}"
                                                                class="btn w-sm btn-danger">Delete it!</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Modal End --}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center text-muted">No laptops recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->

    <!-- Modal for form store -->
    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="exampleModalLabel">Add Laptop</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>

                <form action="{{ route('laptop.store') }}" method="POST">
                    @csrf
                    <div class="modal-body mx-4 my-2">
                        <div class="mb-3">
                            <label for="brand" class="form-label">Brand</label>
                            <input type="text" class="form-control" id="brand" name="brand"
                                placeholder="Dell, HP, Lenovo..." value="{{ old('brand') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="model" class="form-label">Model</label>
                            <input type="text" class="form-control" id="model" name="model" placeholder="Enter Model"
                                value="{{ old('model') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="serial_number" class="form-label">Serial Number</label>
                            <input type="text" class="form-control" id="serial_number" name="serial_number"
                                placeholder="Unique serial number" value="{{ old('serial_number') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="processor" class="form-label">Processor</label>
                            <input type="text" class="form-control" id="processor" name="processor"
                                placeholder="Intel i7 / Apple M3" value="{{ old('processor') }}">
                        </div>
                        <div class="mb-3">
                            <label for="ram_gb" class="form-label">RAM (GB)</label>
                            <input type="number" class="form-control" id="ram_gb" name="ram_gb" min="1"
                                max="1024" value="{{ old('ram_gb') }}">
                        </div>
                        <div class="mb-3">
                            <label for="storage_gb" class="form-label">Storage (GB)</label>
                            <input type="number" class="form-control" id="storage_gb" name="storage_gb" min="1"
                                max="100000" value="{{ old('storage_gb') }}">
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="text" class="form-control" id="price" name="price" placeholder="Enter Price"
                                value="{{ old('price') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="currency" class="form-label">Currency</label>
                            <select class="form-select" id="currency" name="currency" required>
                                @foreach (['USD', 'PKR', 'EUR', 'GBP', 'AED', 'INR', 'CNY', 'AUD', 'CAD'] as $cur)
                                    <option value="{{ $cur }}" {{ old('currency') === $cur ? 'selected' : '' }}>{{ $cur }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="purchase_date" class="form-label">Purchase Date</label>
                            <input type="date" class="form-control" id="purchase_date" name="purchase_date"
                                value="{{ old('purchase_date') }}">
                        </div>
                        <div class="mb-3">
                            <label for="laptop_status" class="form-label">Status</label>
                            <select class="form-select" id="laptop_status" name="status">
                                <option value="">Not set &mdash; defaults to Available</option>
                                @foreach (\App\Models\Laptop::STATUSES as $state)
                                    <option value="{{ $state }}" {{ old('status') === $state ? 'selected' : '' }}>{{ ucfirst($state) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="submit" class="btn btn-success" id="add-btn">Add Laptop</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/libs/prismjs/prismjs.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/list.js/list.js.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/list.pagination.js/list.pagination.js.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/pages/listjs.init.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!--datatable js-->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="{{ URL::asset('assets/js/pages/datatables.init.js') }}"></script>

    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
