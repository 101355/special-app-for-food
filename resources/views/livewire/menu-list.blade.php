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
                            <h4 class="card-title">Menu List</h4>
                            <button type="button" class="btn btn-sm btn-info text-white" wire:click="openCreateModal">
                                Create Menu
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Menu Category</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Image</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($menus as $key => $menu)
                                        <tr>
                                            <td>{{ ($menus->currentPage() - 1) * $menus->perPage() + $key + 1 }}
                                            </td>
                                            <td>{{ $menu->name }}</td>
                                            <td>{{ $menu->menu_category->name }}</td>
                                            <td>{{ $menu->description }}</td>
                                            <td>{{ $menu->price }}</td>
                                            <td><img src="{{ asset('storage/' . $menu->image) }}" alt=""
                                                    width="50"></td>
                                            <td>{{ $menu->status }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-warning text-white"
                                                    wire:click="openEditModal({{ $menu->id }})">Edit</button>
                                                <button class="btn btn-sm btn-danger"
                                                    wire:click="openDeleteModal({{ $menu->id }})">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Showing {{ $menus->firstItem() ?? 0 }} to {{ $menus->lastItem() ?? 0 }} of
                                {{ $menus->total() }} results
                            </div>
                            <div>
                                {{ $menus->links() }}
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
            <div class="modal-dialog modal-md modal-dialog-scrollable modal-dialog-centered" style="max-height: 80vh;">
                <div class="modal-content" style="height: 70vh; overflow-y: auto;">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $menuId ? 'Edit Menu Category' : 'Create Menu Category' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModals"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="menuCategory_id" class="mb-1">Category<span
                                        class="text-danger">*</span></label>
                                <select name="menuCategory_id" id="menuCategory_id" class="form-control">
                                    <option value="">Select Category</option>
                                    @foreach (\App\Models\MenuCategory::all() as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('menuCategory_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="name">Name <span class="text-danger">*</span></label>
                                <input type="text" id="name" wire:model.defer="name" class="form-control"
                                    placeholder="Enter Category name">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="description">Description <span class="text-danger">*</span></label>
                                <textarea id="description" wire:model.defer="description" class="form-control custom-textarea"
                                    placeholder="Enter Category description"></textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="price">Price <span class="text-danger">*</span></label>
                                <input type="number" id="price" wire:model.defer="price" class="form-control"
                                    placeholder="Enter Category price">
                                @error('price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="image">Image <span class="text-danger">*</span></label>
                                <input type="file" id="image" wire:model.defer="image" class="form-control">
                                @error('image')
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
                        <p>Are you sure you want to delete this category?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="closeModals"
                            class="btn btn-dark text-white">Cancel</button>
                        <button type="button" wire:click="delete" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        .custom-textarea {
            height: 80px;
            /* Set desired height */
            resize: none;
            /* Prevent resizing, optional */
        }
    </style>
</div>
