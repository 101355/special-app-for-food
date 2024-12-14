<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tables;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    public $name;
    public $table_no;
    public $capacity;
    // public $status;
    public $tableId;

    public $showCreateModal = false;
    public $showDeleteModal = false;

    protected $paginationTheme = 'bootstrap'; // Optional: For Bootstrap pagination styling

    // Open the modal for creating a new category
    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    // Open the modal for editing an existing category
    public function openEditModal($id)
    {
        $this->edit($id);
        $this->showCreateModal = true;
    }

    // Open the modal for confirming deletion
    public function openDeleteModal($id)
    {
        $this->tableId = $id;
        $this->showDeleteModal = true;
    }

    // Close the modals
    public function closeModals()
    {
        $this->showCreateModal = false;
        $this->showDeleteModal = false;
    }

    // Save new or updated category
    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'table_no' => 'required|string|max:255',
            'capacity' => 'required|numeric|max:15',
        ]);

        if ($this->tableId) {
            $category = Tables::find($this->tableId);
            $category->update([
                'name' => $this->name,
                'table_no' => $this->table_no,
                'capacity' => $this->capacity,
            ]);
        } else {
            Tables::create([
                'name' => $this->name,
                'table_no' => $this->table_no,
                'capacity' => $this->capacity,
            ]);
        }

        session()->flash('message', $this->tableId ? 'Table updated successfully!' : 'Table created successfully!');
        $this->resetForm();
        $this->closeModals();
    }

    public function changeStatus($tableId, $newStatus)
    {
        $table = Tables::find($tableId);

        if ($table) {
            $table->status = $newStatus;
            $table->save();
        }
    }

    // Delete the category
    public function delete()
    {
        Tables::find($this->tableId)->delete();
        session()->flash('delete', 'Category deleted successfully!');
        $this->closeModals();
    }

    // Reset the form fields
    private function resetForm()
    {
        $this->reset(['name', 'tableId', 'table_no', 'capacity']);
    }

    // Load the item data into the form for editing
    public function edit($id)
    {
        $category = Tables::find($id);
        $this->tableId = $id;
        $this->name = $category->name;
        $this->table_no = $category->table_no;
        $this->capacity = $category->capacity;
    }

    public function render()
    {
        $tables = Tables::paginate(10); // Change the number to adjust the items per page
        return view('livewire.table', ['tables' => $tables])->extends('AdminLayout.index');
    }
}
