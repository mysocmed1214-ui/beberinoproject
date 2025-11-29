<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingsModel;
use App\Models\ActivityLogModel;

class System extends BaseController
{
    protected $settingsModel;
    protected $logModel;

    public function __construct()
    {
        $this->settingsModel = new SettingsModel();
        $this->logModel      = new ActivityLogModel();
    }

    // Helper to log activity with real MAC and IP
    // Helper to log activity with real MAC, IP, and connection type
private function logActivity($action, $details = '')
{
    $userId   = session()->get('user_id') ?? 0;
    $username = session()->get('user_name') ?? 'Guest';

    // Get real IP address
    $ip = $this->getRealIpAddress();

    // Get MAC address and connection type
    $networkInfo = $this->getNetworkInfo($ip);
    $mac = $networkInfo['mac'];
    $connectionType = $networkInfo['type'];

    // Insert into activity log table
    $this->logModel->insert([
        'user_id'         => $userId,
        'username'        => $username,
        'action'          => $action,
        'details'         => $details,
        'ip_address'      => $ip,
        'mac_address'     => $mac,
        'connection_type' => $connectionType,
        'created_at'      => date('Y-m-d H:i:s')
    ]);
}

/**
 * Get real IP address (even behind proxies)
 */
private function getRealIpAddress()
{
    $ipKeys = [
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR'
    ];

    foreach ($ipKeys as $key) {
        if (!empty($_SERVER[$key])) {
            $ips = explode(',', $_SERVER[$key]);
            foreach ($ips as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
    }

    return 'UNKNOWN';
}

/**
 * Get MAC address and determine connection type
 */
private function getNetworkInfo($ip)
{
    $mac = 'UNKNOWN';
    $type = 'Unknown';

    // Localhost
    if (in_array($ip, ['127.0.0.1', '::1'])) {
        return ['mac' => 'LOCALHOST', 'type' => 'Localhost'];
    }

    // Attempt to get MAC
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        @exec('getmac', $output);
        if (!empty($output)) {
            foreach ($output as $line) {
                if (preg_match('/([0-9A-Fa-f]{2}([-:])){5}[0-9A-Fa-f]{2}/', $line, $matches)) {
                    $mac = strtoupper($matches[0]);
                    break;
                }
            }
        }
    } else {
        @exec('ifconfig -a', $output);
        if (!empty($output)) {
            foreach ($output as $line) {
                if (preg_match('/([0-9a-fA-F]{2}:){5}[0-9a-fA-F]{2}/', $line, $matches)) {
                    $mac = strtoupper($matches[0]);
                    break;
                }
            }
        }
    }

    if ($mac === 'UNKNOWN') {
        $mac = gethostname();
    }

    // Determine connection type
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
        $type = 'Public Internet';
    } elseif (preg_match('/^(192\.168|10\.|172\.1[6-9]|172\.2[0-9]|172\.3[0-1])\./', $ip)) {
        $type = 'LAN (Local Network)';
    } else {
        $type = 'Peer-to-Peer / Unknown';
    }

    return ['mac' => $mac, 'type' => $type];
}


    // Toggle system mode (AJAX)
    public function toggleMode()
    {
        if (!session()->get('logged_in') || !session()->get('is_admin')) {
            return $this->response->setStatusCode(403)->setBody('Forbidden');
        }

        $currentMode = $this->settingsModel->getValue('system_mode');
        $newMode     = $currentMode === 'online' ? 'maintenance' : 'online';
        $this->settingsModel->setValue('system_mode', $newMode);

        // Log toggle activity
        $this->logActivity('Toggle System Mode', "From $currentMode → $newMode");

        return $this->response->setJSON([
            'status'  => 'success',
            'newMode' => $newMode
        ]);
    }

    // Show system settings page
    public function index()
    {
        $mode = $this->settingsModel->getValue('system_mode');

        return view('admin/system/index', [
            'mode'  => $mode,
            'title' => 'System Settings'
        ]);
    }
}
