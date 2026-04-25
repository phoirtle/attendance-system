<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserModelTest extends TestCase
{
    public function test_isAdmin_returns_true_for_admin_role(): void
    {
        $user = new User(['role' => 'admin']);
        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isUser());
    }

    public function test_isUser_returns_true_for_user_role(): void
    {
        $user = new User(['role' => 'user']);
        $this->assertTrue($user->isUser());
        $this->assertFalse($user->isAdmin());
    }

    public function test_isAdmin_returns_false_for_user_role(): void
    {
        $user = new User(['role' => 'user']);
        $this->assertFalse($user->isAdmin());
    }
}
