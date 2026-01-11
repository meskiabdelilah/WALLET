<?php
namespace Src\Classes;

use Exception;
use Src\Classes\Database;

class Transaction
{
    private $db;

    public function __construct()
    {
        require_once __DIR__ . '/../Config/db.php';
        $this->db = Database::getInstance();
    }

    public function getTotalExpenses($userId)
    {
        $sql = "SELECT SUM(amount) as total FROM transactions 
                WHERE user_id = ? AND type = 'expense' AND deleted_at IS NULL";
        $result = $this->db->query($sql, [$userId]);
        $data = $result->fetch();
        return $data['total'] ?? 0;
    }

    public function getTotalDeposits($userId)
    {
        $sql = "SELECT SUM(amount) as total FROM transactions 
                WHERE user_id = ? AND type = 'deposit' AND deleted_at IS NULL";
        $result = $this->db->query($sql, [$userId]);
        $data = $result->fetch();
        return $data['total'] ?? 0;
    }

    public function getMonthlyTransactionCount($userId)
    {
        $sql = "SELECT COUNT(*) as count FROM transactions 
                WHERE user_id = ? AND MONTH(created_at) = MONTH(NOW()) 
                AND YEAR(created_at) = YEAR(NOW()) AND deleted_at IS NULL";
        $result = $this->db->query($sql, [$userId]);
        $data = $result->fetch();
        return $data['count'] ?? 0;
    }

    public function getRecentTransactions($userId, $limit = 3)
    {
        $sql = "SELECT * FROM transactions 
                WHERE user_id = ? AND deleted_at IS NULL 
                ORDER BY created_at DESC LIMIT ?";
        $result = $this->db->query($sql, [$userId, $limit]);
        return $result->fetchAll();
    }

    public function getBalance($userId)
    {
        $deposits = $this->getTotalDeposits($userId);
        $expenses = $this->getTotalExpenses($userId);
        return $deposits - $expenses;
    }

    public function addExpense($userId, $title, $amount, $category = null)
    {
        if (empty($title) || empty($amount) || $amount <= 0) {
            return false;
        }

        $sql = "INSERT INTO transactions (user_id, title, amount, type, created_at) 
                VALUES (?, ?, ?, 'expense', NOW())";
        
        try {
            $this->db->query($sql, [$userId, $title, $amount]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
