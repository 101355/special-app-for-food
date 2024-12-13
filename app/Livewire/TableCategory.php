<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TableCategorys;

class TableCategory extends Component
{
    use WithPagination;

    public $name;
    public $tableCategoryId;

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
        $this->tableCategoryId = $id;
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
        ]);

        if ($this->tableCategoryId) {
            $category = TableCategorys::find($this->tableCategoryId);
            $category->update([
                'name' => $this->name,
            ]);
        } else {
            TableCategorys::create([
                'name' => $this->name,
            ]);
        }

        session()->flash('message', $this->tableCategoryId ? 'Category updated successfully!' : 'Category created successfully!');
        $this->resetForm();
        $this->closeModals();
    }

    // Delete the category
    public function delete()
    {
        TableCategorys::find($this->tableCategoryId)->delete();
        session()->flash('delete', 'Category deleted successfully!');
        $this->closeModals();
    }

    // Reset the form fields
    private function resetForm()
    {
        $this->reset(['name', 'tableCategoryId']);
    }

    // Load the item data into the form for editing
    public function edit($id)
    {
        $category = TableCategorys::find($id);
        $this->tableCategoryId = $id;
        $this->name = $category->name;
    }

    public function render()
    {
        $tableCategories = TableCategorys::paginate(10); // Change the number to adjust the items per page
        return view('livewire.table-category', ['tableCategories' => $tableCategories])->extends('AdminLayout.index');
    }
}
