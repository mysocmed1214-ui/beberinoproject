<?php namespace App\Controllers;
use App\Models\UserModel;
use App\Models\ActivityLogModel;

class Auth extends BaseController
{
    public function login()
    {
        echo view('templates/header');
        echo view('auth/login');
        echo view('templates/footer');
    }

    public function loginPost()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = (new UserModel())->where('email', $email)->first();

        if ($user && $this->checkPassword($password, $user['password'])) {
            session()->set([
                'user_id'   => $user['id'],
                'user_name' => $user['fullname'],
                'is_admin'  => $user['is_admin'],
                'logged_in' => true
            ]);

            // Log network activity
            $this->logNetworkActivity($user['id'], $user['fullname'], 'User logged in');

            if ($user['is_admin'] == 1) {
                return redirect()->to('/admin/dashboard');
            } else {
                return redirect()->to('/');
            }
        }

        session()->setFlashdata('error', 'Invalid credentials.');
        return redirect()->back();
    }

    public function register()
    {
        echo view('templates/header');
        echo view('auth/register');
        echo view('templates/footer');
    }

    public function registerPost()
{
    $fullname = $this->request->getPost('fullname');
    $email = $this->request->getPost('email');
    $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

    $model = new UserModel();

    if (!$model->insert([
        'fullname'  => $fullname,
        'email'     => $email,
        'password'  => $password,
        'is_admin'  => 0
    ])) {
        echo '<pre>';
        print_r($model->errors());
        exit;
    }

    $this->logNetworkActivity($model->getInsertID(), $fullname, 'User registered');

    session()->setFlashdata('success', 'Registration successful. Please login.');
    return redirect()->to('/auth/login');
}


    public function logout()
    {
        // Log network activity
        $this->logNetworkActivity(session()->get('user_id'), session()->get('user_name') ?? 'Guest', 'User logged out');

        session()->destroy();
        return redirect()->to('/');
    }

    public function admin_login()
    {
        echo view('templates/header');
        echo view('auth/admin_login');
        echo view('templates/footer');
    }

    public function adminLoginPost()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if ($user) {
            // ✅ Accept both hashed and plain passwords
            $passwordMatch = password_verify($password, $user['password']) || $user['password'] === $password;

            if ((int)$user['is_admin'] === 1 && $passwordMatch) {
                // Set session
                session()->set([
                    'user_id'   => $user['id'],
                    'user_name' => $user['fullname'],
                    'is_admin'  => 1,
                    'logged_in' => true
                ]);

                // Log network activity
                $this->logNetworkActivity($user['id'], $user['fullname'], 'Admin logged in');

                // ✅ Debug message to confirm redirection
                return redirect()->to('/admin/dashboard')->with('success', 'Welcome Admin!');
            }
        }

        // If credentials are wrong
        session()->setFlashdata('error', 'Invalid admin credentials.');
        return redirect()->back();
    }

    /**
     * ✅ Helper function to check both hashed and plain passwords
     */
    private function checkPassword($inputPassword, $storedPassword)
    {
        // Case 1: Hashed password (bcrypt or argon)
        if (password_get_info($storedPassword)['algo'] !== 0) {
            return password_verify($inputPassword, $storedPassword);
        }

        // Case 2: Plain text password (legacy support)
        return $inputPassword === $storedPassword;
    }

    public function admin_register()
    {
        echo view('templates/header');
        echo view('auth/admin_register');
        echo view('templates/footer');
    }

    public function adminRegisterPost()
    {
        $fullname = $this->request->getPost('fullname');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $model = new UserModel();

        // Check if email already exists
        if ($model->where('email', $email)->first()) {
            session()->setFlashdata('error', 'Email already registered.');
            return redirect()->back();
        }

        // Insert admin
        $model->insert([
            'fullname'  => $fullname,
            'email'     => $email,
            'password'  => password_hash($password, PASSWORD_DEFAULT),
            'is_admin'  => 1
        ]);

        // Log network activity
        $this->logNetworkActivity($model->getInsertID(), $fullname, 'Admin registered');

        session()->setFlashdata('success', 'Admin registration successful. You can now log in.');
        return redirect()->to('/auth/admin_login');
    }

    public function admin_logout()
    {
        // Log network activity
        $this->logNetworkActivity(session()->get('user_id'), session()->get('user_name') ?? 'Guest', 'Admin logged out');

        session()->destroy(); // ✅ Clear session
        return redirect()->to('/auth/admin_login')->with('success', 'You have been logged out.');
    }

    /**
     * ✅ Network logging helper
     */
    private function logNetworkActivity($userId, $username, $action)
    {
        $logModel = new ActivityLogModel();

        // Get IP address
        $ipAddress = $this->request->getIPAddress();

        // Try to get MAC address (peer-to-peer LAN or Wi-Fi)
        $macAddress = null;

        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') { // Linux/Mac
            $mac = shell_exec("arp -n $ipAddress | awk '/$ipAddress/ {print $3}'");
            $macAddress = trim($mac) ?: null;
        } else { // Windows
            $mac = shell_exec("arp -a $ipAddress");
            $matches = [];
            if (preg_match('/([0-9A-F]{2}[-:]){5}([0-9A-F]{2})/i', $mac, $matches)) {
                $macAddress = $matches[0];
            }
        }

        // Save log
        $logModel->insert([
            'user_id'    => $userId,
            'username'   => $username,
            'action'     => $action,
            'ip_address' => $ipAddress,
            'mac_address'=> $macAddress
        ]);
    }
}
