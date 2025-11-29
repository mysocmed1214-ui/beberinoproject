<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ActivityLogModel;

class ActivityLogs extends BaseController
{
    protected $logModel;

    public function __construct()
    {
        $this->logModel = new ActivityLogModel();
    }

    // Show logs page
    public function index()
    {
        if (!session()->get('logged_in') || !session()->get('is_admin')) {
            return redirect()->to('/auth/admin_login');
        }

        $logs = $this->logModel->orderBy('created_at', 'DESC')->findAll();

        return view('admin/activity_logs/index', [
            'logs'  => $logs,
            'title' => 'Activity Logs'
        ]);
    }

    // Clear all logs via AJAX
    public function clear()
    {
        if (!session()->get('logged_in') || !session()->get('is_admin')) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Unauthorized'
            ])->setStatusCode(403);
        }

        try {
            $this->logModel->builder()->truncate();

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'All activity logs have been cleared.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Failed to clear logs: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    // Helper to log activity
    private function logActivity($action, $details = '')
    {
        $userId   = session()->get('user_id') ?? 0;
        $username = session()->get('user_name') ?? 'Guest';

        // Get real IP address (IPv4 or IPv6)
        $ip = $this->getRealIpAddress();

        // Get MAC address and connection type
        $networkInfo = $this->getNetworkInfo($ip);
        $mac = $networkInfo['mac'];
        $connectionType = $networkInfo['type'];

        // Save log to database
        $this->logModel->insert([
            'user_id'        => $userId,
            'username'       => $username,
            'action'         => $action,
            'ip_address'     => $ip,
            'mac_address'    => $mac,
            'connection_type'=> $connectionType,
            'details'        => $details,
            'created_at'     => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get real IP address (IPv4 or IPv6), even behind proxies
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

        return $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    }

    /**
     * Get MAC address and determine connection type (IPv4 or IPv6)
     */
    private function getNetworkInfo($ip)
    {
        $mac = 'UNKNOWN';
        $type = 'Unknown';

        // Localhost detection (IPv4 and IPv6)
        if (in_array($ip, ['127.0.0.1', '::1']) || str_starts_with($ip, 'fe80::')) {
            return ['mac' => 'LOCALHOST', 'type' => 'Localhost'];
        }

        // Attempt to get MAC from ARP
        $output = [];
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            @exec("arp -a " . escapeshellarg($ip), $output);
        } else {
            @exec("arp -n " . escapeshellarg($ip), $output);
        }

        if (!empty($output)) {
            foreach ($output as $line) {
                if (preg_match('/([0-9A-Fa-f]{2}([-:])){5}[0-9A-Fa-f]{2}/', $line, $matches)) {
                    $mac = strtoupper($matches[0]);
                    break;
                }
            }
        }

        // Fallback if MAC not found
        if ($mac === 'UNKNOWN') {
            $mac = gethostname();
        }

        // Determine connection type
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            $type = 'Public Internet';
        } elseif (preg_match('/^(192\.168|10\.|172\.1[6-9]|172\.2[0-9]|172\.3[0-1])\./', $ip)) {
            $type = 'LAN (Local Network)';
        } elseif (str_starts_with($ip, 'fe80::')) {
            $type = 'LAN IPv6 (Link-Local)';
        } else {
            $type = 'Peer-to-Peer / Unknown';
        }

        return ['mac' => $mac, 'type' => $type];
    }
}
