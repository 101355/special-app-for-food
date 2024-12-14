<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MenuCategory;
use Livewire\WithPagination;

class MenuCategoryList extends Component
{
    use WithPagination;

    public $categoryId;
    public $name;
    public $showCreateModal = false;
    public $showDeleteModal = false;

    // Open the modal for creating a new category
    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function resetForm()
    {
        $this->reset(['name', 'categoryId']);
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
        $this->categoryId = $id;
        $this->showDeleteModal = true;
    }

    // Close the modals
    public function closeModals()
    {
        $this->showCreateModal = false;
        $this->showDeleteModal = false;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        if ($this->categoryId) {
            $category = MenuCategory::find($this->categoryId);
            $category->update([
                'name' => $this->name,
            ]);
        } else {
            MenuCategory::create([
                'name' => $this->name,
            ]);
        }

        session()->flash('message', $this->categoryId ? 'Category updated successfully!' : 'Category created successfully!');
        $this->resetForm();
        $this->closeModals();
    }

    public function delete()
    {
        MenuCategory::find($this->categoryId)->delete();
        session()->flash('delete', 'Category deleted successfully!');
        $this->closeModals();
    }

    public function edit($id)
    {
        $category = MenuCategory::find($id);
        $this->categoryId = $id;
        $this->name = $category->name;
    }
    public function render()
    {
        $categories = MenuCategory::paginate(10);
        return view('livewire.menu-category-list', ['categories' => $categories])->extends('AdminLayout.index');
    }
}
