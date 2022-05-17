<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    /**
     * Unauthenticated user cannot retrieve information
     *
     * @return void
     */
    public function test_unauthenticated_user_cannot_get_info(): void
    {
        $response = $this->get("/api/user");

        $response->assertRedirect("api/login");
    }

    /**
     * User can retrieve information about himself
     *
     * @return void
     */
    public function test_user_can_get_information_about_himself(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get("/api/user");
        $response->assertSuccessful();
        $this->assertJson($response->getContent());
        $json_response = json_decode($response->getContent());
        $this->assertEquals($json_response->id, $user->id);
    }

    /**
     * User can only retrieve information about himself
     *
     * @return void
     */
    public function test_user_can_only_get_information_about_himself(): void
    {
        $user = User::factory()->create();
        $user_fake = User::factory()->create();
        $response = $this->actingAs($user_fake)->get("/api/user");
        $response->assertSuccessful();
        $this->assertJson($response->getContent());
        $json_response = json_decode($response->getContent());
        $this->assertNotEquals($json_response->id, $user->id);
    }

    /**
     * Unauthenticated user cannot update information
     *
     * @return void
     */
    public function test_unauthenticated_user_cannot_update_info(): void
    {
        $response = $this->put("/api/user", [
            "lastname" => "test"
        ]);

        $response->assertRedirect("api/login");
    }

    /**
     * User can update information about himself
     *
     * @return void
     */
    public function test_user_can_update_information_about_himself(): void
    {
        $user = User::factory()->create();
        $lastname = $this->faker->lastName();
        $firstname = $this->faker->firstName();
        $username = $this->faker->userName();
        $response = $this->actingAs($user)->put("api/user", [
            "lastname" => $lastname,
            "firstname" => $firstname,
            "username" => $username
        ]);
        $response->assertSuccessful();
        $this->assertEquals('"Updated"', $response->getContent());
        $this->assertDatabaseHas("users",[
            "id" => $user->id,
            "lastname" => $lastname,
            "firstname" => $firstname,
            "username" => $username
        ]);
    }

    /**
     * User can update his password
     *
     * @return void
     */
    public function test_user_can_update_password(): void
    {
        $user = User::factory()->create();
        $password = $this->faker->password();

        $response = $this->actingAs($user)->put("api/user",[
            "password" => $password,
            "password_confirmation" => $password,
        ]);
        $response->assertSuccessful();
        $this->assertEquals('"Updated"', $response->getContent());
        $user = User::find($user->id);
        $this->assertTrue(Hash::check($password, $user->password));
    }

    /**
     * User cannot update password if password and password_confirmation are not the same
     *
     * @return void
     */
    public function test_user_cannot_update_password_if_missing_information(): void
    {
        $user = User::factory()->create();
        $password = $this->faker->password();

        $response = $this->actingAs($user)->put("api/user",[
            "password" => $password,
            "password_confirmation" => "something",
        ]);
        $response->assertStatus(403);
        $user = User::find($user->id);
        $this->assertFalse(Hash::check($password, $user->password));
    }
}
