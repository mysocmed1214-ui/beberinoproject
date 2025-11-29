<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TypeModel;

class Types extends BaseController
{
    protected $typeModel;

    public function __construct()
    {
        $this->typeModel = new TypeModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Product Types',
            'types' => $this->typeModel->orderBy('created_at', 'DESC')->findAll(),
        ];
        return view('admin/types/index', $data);
    }

    public function store()
    {
        $this->typeModel->insert([
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/admin/types')->with('success', 'Product type added successfully!');
    }

    public function delete($id)
    {
        $this->typeModel->delete($id);
        return redirect()->to('/admin/types')->with('success', 'Product type deleted successfully!');
    }
}
