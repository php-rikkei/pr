<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChangPasswordTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testChangePassword()
    {
        $this->browse(function ($first, $second, $third, $fourth) {
            $first->loginAs(User::find(1))
                ->visit('/changepassword')
                ->value('#password', '123456')
                ->value('#password_confirmation', '123456')
                ->press('#submit')
                ->assertSee('You are logged in');
            $second->loginAs(User::find(1))
                ->visit('/changepassword')
                ->value('#password', '12345')
                ->value('#password_confirmation', '12345')
                ->press('#submit')
                ->assertSee('The password must be at least 6 characters');
            $third->loginAs(User::find(1))
                ->visit('/changepassword')
                ->value('#password', '123456')
                ->value('#password_confirmation', '1235678')
                ->press('#submit')
                ->assertSee('The password confirmation does not match');
            $fourth->loginAs(User::find(1))
                ->visit('/changepassword')
                ->value('#password', '')
                ->value('#password_confirmation', '')
                ->press('#submit')
                ->assertSee('Change password');
        });
    }
    public function testDatabase()
    {
        // Make call to application...

        $this->assertDatabaseHas('users', [
            'email' => 'admin@gmail.com',
            'username' => 'admin',
        ]);
    }
}
