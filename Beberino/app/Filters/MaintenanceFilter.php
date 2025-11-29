<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\SettingsModel;

class MaintenanceFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $settings = new SettingsModel();
        $mode = $settings->getValue('system_mode');

        if($mode === 'maintenance' && !session()->get('is_admin')) {
            return redirect()->to('/maintenance');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
