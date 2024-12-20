<div class="main-panel">
    <div class="content-wrapper">

        <div class="row">
            <!-- Session Alert -->
            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Session Alert -->
            @if (session()->has('delete'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('delete') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="col-12 grid-margin stretch-card">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title">Tables</h4>
                            <button type="button" class="btn btn-sm btn-info text-white" wire:click="openCreateModal">
                                Create Table
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Table Number</th>
                                        <th>Capacity</th>
                                        {{-- <th>Description</th> --}}
                                        <th>Status</th>
                                        <th>Change Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tables as $key => $table)
                                        <tr>
                                            <td>{{ ($tables->currentPage() - 1) * $tables->perPage() + $key + 1 }}</td>
                                            <td>{{ $table->name }}</td>
                                            <td>{{ $table->table_no }}</td>
                                            <td>{{ $table->capacity }}</td>
                                            {{-- <td>{{ $table->description }}</td> --}}
                                            <td>
                                                <span
                                                    style="
                                                        padding: 5px 10px;
                                                        color: white;
                                                        border-radius: 5px;
                                                        background-color:
                                                        {{ $table->status == 'available' ? 'green' : ($table->status == 'occupied' ? 'red' : 'orange') }};
                                                    ">
                                                    {{ ucfirst($table->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <select
                                                    wire:change="changeStatus({{ $table->id }}, $event.target.value)"
                                                    class="form-select">
                                                    <option value="available"
                                                        {{ $table->status == 'available' ? 'selected' : '' }}>Available
                                                    </option>
                                                    <option value="occupied"
                                                        {{ $table->status == 'occupied' ? 'selected' : '' }}>Occupied
                                                    </option>
                                                    <option value="reserved"
                                                        {{ $table->status == 'reserved' ? 'selected' : '' }}>Reserved
                                                    </option>
                                                </select>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-warning text-white"
                                                    wire:click="openEditModal({{ $table->id }})">Edit</button>
                                                <button class="btn btn-sm btn-danger"
                                                    wire:click="openDeleteModal({{ $table->id }})">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Showing {{ $tables->firstItem() ?? 0 }} to {{ $tables->lastItem() ?? 0 }} of
                                {{ $tables->total() }} results
                            </div>
                            <div>
                                {{ $tables->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create / Edit Modal -->
    @if ($showCreateModal)
        <div class="modal fade show d-block" style="background: rgba(0, 0, 0, 0.5);" tabindex="-1">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $tableId ? 'Edit Table' : 'Create Table' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModals"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="name">Name <span class="text-danger">*</span></label>
                                <input type="text" id="name" wire:model.defer="name" class="form-control"
                                    placeholder="Enter table name">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="table_no">Table Number <span class="text-danger">*</span></label>
                                <input type="text" id="table_no" wire:model.defer="table_no" class="form-control"
                                    placeholder="Enter table number">
                                @error('table_no')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="capacity">Capacity <span class="text-danger">*</span></label>
                                <input type="number" id="capacity" wire:model.defer="capacity" class="form-control"
                                    placeholder="Enter table capacity">
                                @error('capacity')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="closeModals" class="btn btn-dark text-white">Cancel</button>
                        <button type="button" wire:click="save" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div class="modal fade show d-block" style="background: rgba(0, 0, 0, 0.5);" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Confirmation</h5>
                        <button type="button" class="btn-close" wire:click="closeModals"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this table?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="closeModals" class="btn btn-dark text-white">Cancel</button>
                        <button type="button" wire:click="delete" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
