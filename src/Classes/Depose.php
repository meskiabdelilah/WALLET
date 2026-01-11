<?php
namespace Src\Classes;

use Exception;
use Src\Classes\Database;

class Depose
{
    private $db;

    public function __construct()
    {
        require_once __DIR__ . '/../Config/db.php';
        $this->db = Database::getInstance();
    }

    public function addDeposit($userId, $title, $amount, $source = 'salary')
    {
        if (empty($title) || empty($amount) || $amount <= 0) {
            return false;
        }

        $sql = "INSERT INTO transactions (user_id, title, amount, type, created_at) 
                VALUES (?, ?, ?, 'deposit', NOW())";
        
        try {
            $this->db->query($sql, [$userId, $title, $amount]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getUserDeposits($userId, $limit = null)
    {
        $sql = "SELECT * FROM transactions 
                WHERE user_id = ? AND type = 'deposit' AND deleted_at IS NULL 
                ORDER BY created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT ?";
            $result = $this->db->query($sql, [$userId, $limit]);
        } else {
            $result = $this->db->query($sql, [$userId]);
        }
        
        return $result->fetchAll();
    }

    public function getMonthlyDeposits($userId, $month = null, $year = null)
    {
        $month = $month ?: date('m');
        $year = $year ?: date('Y');
        
        $sql = "SELECT SUM(amount) as total FROM transactions 
                WHERE user_id = ? AND type = 'deposit' 
                AND MONTH(created_at) = ? AND YEAR(created_at) = ? 
                AND deleted_at IS NULL";
        
        $result = $this->db->query($sql, [$userId, $month, $year]);
        $data = $result->fetch();
        return $data['total'] ?? 0;
    }

    public function deleteDeposit($depositId, $userId)
    {
        $sql = "UPDATE transactions SET deleted_at = NOW() 
                WHERE id = ? AND user_id = ? AND type = 'deposit'";
        
        try {
            $this->db->query($sql, [$depositId, $userId]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateDeposit($depositId, $userId, $title, $amount)
    {
        if (empty($title) || empty($amount) || $amount <= 0) {
            return false;
        }

        $sql = "UPDATE transactions SET title = ?, amount = ?, updated_at = NOW() 
                WHERE id = ? AND user_id = ? AND type = 'deposit' AND deleted_at IS NULL";
        
        try {
            $this->db->query($sql, [$title, $amount, $depositId, $userId]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}