<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminLogoutTest extends TestCase
{
    /** @test */
    public function an_admin_can_logout()
    {
        //Having / Giveng / Arrange
        auth('admin')->login($this->createAdmin());

        $this->assertAuthenticated('admin');
        //when / Act
        $this->post('admin/logout');

        //Then / Assert

        $this->assertGuest('admin');
    }

    /** @test */
    public function loggin_out_as_an_admin_does_not_terminate_the_user_session()
    {
        //Having / Giveng / Arrange
        auth('admin')->login($this->createAdmin());
        auth('web')->login($this->createUser());

        $adminSessionName = auth('admin')->getName();
        $webSessionName = auth('web')->getName();



        $this->assertAuthenticated('admin');
        $this->assertAuthenticated('web');
        //when / Act
       $response = $this->post('admin/logout');

        //Then / Assert

        $response->assertRedirect('/')
            ->assertSessionHas($webSessionName)
            ->assertSessionMissing($adminSessionName);

        $this->assertGuest('admin');
        $this->assertAuthenticated('web');
    }
}
