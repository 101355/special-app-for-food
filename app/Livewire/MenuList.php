<?php

namespace App\Livewire;

use App\Models\Menu;
use Livewire\Component;

class MenuList extends Component
{

    public $showCreateModal = false;
    public $showDeleteModal = false;
    public $menuId;
    public $name;
    public $description;
    public $image;
    // public $status;

    // Open the modal for creating a new category
    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function resetForm()
    {
        $this->reset(['name', 'menuId']);
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
        $this->menuId = $id;
        $this->showDeleteModal = true;
    }

    // Close the modals
    public function closeModals()
    {
        $this->showCreateModal = false;
        $this->showDeleteModal = false;
    }
    public function render()
    {
        $menus = Menu::paginate(10);
        return view('livewire.menu-list', ['menus' => $menus])->extends('AdminLayout.index');
    }
}
