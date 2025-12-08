<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Income Categories
        $incomeCategories = [
            ['name' => 'Salary', 'type' => 'income', 'color' => '#28a745', 'icon' => 'fas fa-briefcase'],
            ['name' => 'Business', 'type' => 'income', 'color' => '#17a2b8', 'icon' => 'fas fa-briefcase'],
            ['name' => 'Investment', 'type' => 'income', 'color' => '#6f42c1', 'icon' => 'fas fa-chart-line'],
            ['name' => 'Freelance', 'type' => 'income', 'color' => '#fd7e14', 'icon' => 'fas fa-laptop'],
            ['name' => 'Gift', 'type' => 'income', 'color' => '#e83e8c', 'icon' => 'fas fa-gift'],
            ['name' => 'Other Income', 'type' => 'income', 'color' => '#6c757d', 'icon' => 'fas fa-plus-circle'],
        ];

        // Expense Categories
        $expenseCategories = [
            ['name' => 'Food & Dining', 'type' => 'expense', 'color' => '#dc3545', 'icon' => 'fas fa-utensils'],
            ['name' => 'Transportation', 'type' => 'expense', 'color' => '#ffc107', 'icon' => 'fas fa-car'],
            ['name' => 'Shopping', 'type' => 'expense', 'color' => '#e83e8c', 'icon' => 'fas fa-shopping-bag'],
            ['name' => 'Entertainment', 'type' => 'expense', 'color' => '#6f42c1', 'icon' => 'fas fa-film'],
            ['name' => 'Bills & Utilities', 'type' => 'expense', 'color' => '#17a2b8', 'icon' => 'fas fa-file-invoice'],
            ['name' => 'Healthcare', 'type' => 'expense', 'color' => '#28a745', 'icon' => 'fas fa-heartbeat'],
            ['name' => 'Education', 'type' => 'expense', 'color' => '#007bff', 'icon' => 'fas fa-graduation-cap'],
            ['name' => 'Travel', 'type' => 'expense', 'color' => '#fd7e14', 'icon' => 'fas fa-plane'],
            ['name' => 'Rent/Mortgage', 'type' => 'expense', 'color' => '#dc3545', 'icon' => 'fas fa-home'],
            ['name' => 'Insurance', 'type' => 'expense', 'color' => '#6c757d', 'icon' => 'fas fa-shield-alt'],
            ['name' => 'Savings', 'type' => 'expense', 'color' => '#28a745', 'icon' => 'fas fa-piggy-bank'],
            ['name' => 'Other Expense', 'type' => 'expense', 'color' => '#6c757d', 'icon' => 'fas fa-minus-circle'],
        ];

        foreach ($incomeCategories as $category) {
            Category::create($category);
        }

        foreach ($expenseCategories as $category) {
            Category::create($category);
        }
    }
}
