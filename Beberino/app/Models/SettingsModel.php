<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingsModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['key', 'value'];

    public function getValue($key)
    {
        $row = $this->where('key', $key)->first();
        return $row ? $row['value'] : null;
    }

    public function setValue($key, $value)
    {
        if ($this->where('key', $key)->first()) {
            return $this->update($this->where('key', $key)->first()['id'], ['value' => $value]);
        } else {
            return $this->insert(['key' => $key, 'value' => $value]);
        }
    }
}
