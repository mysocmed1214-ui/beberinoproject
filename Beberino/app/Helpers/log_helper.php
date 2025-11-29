<?php

use App\Models\ActivityLogModel;

/**
 * Log user activity with network info
 *
 * @param int|null $user_id
 * @param string $username
 * @param string $action
 * @param array|null $extra Optional extra data (like $_SERVER)
 */
function log_activity($user_id = null, $username = 'Guest', $action = 'Performed action', $extra = null)
{
    $logModel = new ActivityLogModel();

    // Fallback if username is missing (to prevent NULL)
    if (empty($username)) {
        $username = 'System';
    }

    // Get real IP address
    $ip_address = get_real_ip_address();

    // Get MAC and connection type
    $networkInfo = get_network_info($ip_address);
    $mac_address = $networkInfo['mac'];
    $connection_type = $networkInfo['type'];

    $logModel->insert([
        'user_id'         => $user_id ?? 0,
        'username'        => $username,
        'action'          => $action,
        'ip_address'      => $ip_address,
        'mac_address'     => $mac_address,
        'connection_type' => $connection_type,
        'details'         => $extra ? json_encode($extra) : null,
        'created_at'      => date('Y-m-d H:i:s')
    ]);
}

/**
 * Get real IP address
 */
function get_real_ip_address()
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
 * Get MAC address and connection type
 */
function get_network_info($ip)
{
    $mac = 'UNKNOWN';
    $type = 'Unknown';

    // Localhost or IPv6 link-local
    if (in_array($ip, ['127.0.0.1', '::1']) || str_starts_with($ip, 'fe80::')) {
        return ['mac' => 'LOCALHOST', 'type' => 'Localhost'];
    }

    // Attempt to get MAC address via ARP
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

    // Fallback MAC
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
