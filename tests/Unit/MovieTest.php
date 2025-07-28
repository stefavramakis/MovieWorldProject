<?php

namespace Tests\Unit;

use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class MovieTest extends TestCase
{
    use RefreshDatabase;

    public function test_movie_belongs_to_user()
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $movie->user);
        $this->assertEquals($user->id, $movie->user->id);
    }

    public function test_movie_can_be_liked_and_hated()
    {
        $user = User::factory()->create();
        $movie = Movie::factory()->create(['user_id' => $user->id]);

        $movie->votes()->create([
            'user_id' => $user->id,
            'type' => 'like',
        ]);

        $this->assertEquals(1, $movie->likes()->count());
        $this->assertEquals(0, $movie->hates()->count());
    }

    public function test_guest_can_view_movies()
    {
        $movie = Movie::factory()->create();

        $response = $this->get(route('movies.index'));

        $response->assertStatus(200);
        $response->assertSee($movie->title);
    }

    public function test_authenticated_user_can_add_movie()
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('movies.store'), [
            'title' => 'Interstellar',
            'description' => 'Sci-fi epic',
        ]);

        $response->assertRedirect(route('movies.index'));
        $this->assertDatabaseHas('movies', ['title' => 'Interstellar']);
    }
}
