<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tests\TestCase;

class PostCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_index_unauthenticated_users()
    {
        $response = $this->get(route('post.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_post_index_page_for_auth_users()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $response = $this->get(route('post.index'));
        $response->assertOk(); // this is equals to assertStatus(200)
    }

    public function test_get_invalid_posts_for_valid_user()
    {
        $user = User::factory()->create();
        Post::factory(10)->create();

        $response = $this->actingAs($user)->get(route('post.show', 50));
        $response->assertStatus(404);
    }

    public function test_get_valid_posts_for_auth_user()
    {
        $user = User::factory()->create();
        $posts = Post::factory(10)->create();

        $response = $this->actingAs($user)->get(route('post.show', $posts->first()->id));
        $response->assertStatus(200);
    }

    public function test_store_new_post()
    {
        $user = User::factory()->create();
        $params = [];
        $response = $this->actingAs($user)->post(route('post.store'), $params);
        $response->assertSessionHasErrors(['title', 'image', 'short_description', 'full_details']);

        $params = ['title' => 'test post is here'];
        $response = $this->actingAs($user)->post(route('post.store'), $params);
        $response->assertSessionHasErrors(['image', 'short_description', 'full_details']);

        $params = ['title' => 'test title', 'short_description' => 'lorem ipsum dolor emet sit.'];
        $response = $this->actingAs($user)->post(route('post.store'), $params);
        $response->assertSessionHasErrors(['image', 'full_details']);

        $params = ['title' => 'test title', 'short_description' => 'lorem ipsum dolor emet sit.', 'full_details' => 'ha ha ha lorem ipsum dolor emit sit'];
        $response = $this->actingAs($user)->post(route('post.store'), $params);
        $response->assertSessionHasErrors(['image']);

        $params = [
            'title' => 'test title',
            'short_description' => 'lorem ipsum dolor emet sit.',
            'full_details' => 'ha ha ha lorem ipsum dolor emit sit',
            'image' => 'new UploadedFile(public_path(\'img/1.png\'), \'1.png\', null, null, true)'
        ];
        $response = $this->actingAs($user)->post(route('post.store'), $params);
        $response->assertSessionHasErrors(['image']);

        $params = [
            'title' => 'test title',
            'short_description' => 'lorem ipsum dolor emet sit.',
            'full_details' => 'ha ha ha lorem ipsum dolor emit sit',
            'image' => new UploadedFile(public_path('img/1.png'), '1.png', null, null, true)
        ];

        $this->actingAs($user)
            ->from(route('post.index'))
            ->post(route('post.store'), $params)
            ->assertStatus(302)
            ->assertRedirect(route('post.index'));
    }


    public function test_update_old_post()
    {
        $user = User::factory()->create();
        Post::factory(10)->create();
        $post = Post::query()->inRandomOrder()->first();

        $params = [];
        $response = $this->actingAs($user)->put(route('post.update', $post->id), $params);
        $response->assertInvalid(['title', 'short_description', 'full_details']);

        $params = ['title' => 'test post is here'];
        $response = $this->actingAs($user)->put(route('post.update', $post->id), $params);
        $response->assertSessionHasErrors(['short_description', 'full_details']);

        $params = ['title' => 'test title', 'short_description' => 'lorem ipsum dolor emet sit.'];
        $response = $this->actingAs($user)->put(route('post.update', $post->id), $params);
        $response->assertSessionHasErrors(['full_details']);

        $params = ['title' => 'test title', 'short_description' => 'lorem ipsum dolor emet sit.', 'full_details' => 'ha ha ha lorem ipsum dolor emit sit'];
        $response = $this->actingAs($user)->put(route('post.update', $post->id), $params);
        $response->assertSessionHasNoErrors();

        $params = [
            'title' => 'test title',
            'short_description' => 'lorem ipsum dolor emet sit.',
            'full_details' => 'ha ha ha lorem ipsum dolor emit sit',
            'image' => 'new UploadedFile(public_path(\'img/1.png\'), \'1.png\', null, null, true)'
        ];
        $response = $this->actingAs($user)->put(route('post.update', $post->id), $params);
        $response->assertSessionHasErrors(['image']);

        $params = [
            'title' => 'test title',
            'short_description' => 'lorem ipsum dolor emet sit.',
            'full_details' => 'ha ha ha lorem ipsum dolor emit sit',
            'image' => new UploadedFile(public_path('img/1.png'), '1.png', null, null, true)
        ];

        $this->actingAs($user)
            ->from(route('post.index'))
            ->put(route('post.update', $post->id), $params)
            ->assertStatus(302)
            ->assertRedirect(route('post.index'));
    }

    public function test_post_delete()
    {
        $user = User::factory()->create();
        Post::factory(10)->create();
        $post = Post::query()->inRandomOrder()->first();

        $this->actingAs($user)
            ->delete(route('post.delete', $post->id))
            ->assertOk();
    }

    public function test_delete_post_with_no_auth()
    {
        User::factory(10)->create();
        Post::factory(10)->create();
        $post = Post::query()->inRandomOrder()->first();

        $this->delete(route('post.delete', $post->id))
            ->assertStatus(302)
            ->assertRedirect('login');
    }

    public function test_delete_post_with_no_auth_no_data()
    {
        $this->delete(route('post.delete', 10))
            ->assertStatus(302)
            ->assertRedirect('login');
    }

    public function test_delete_post_with_no_data()
    {
        $this->actingAs(User::factory()->create())
            ->delete(route('post.delete', 10))
            ->assertStatus(404);
    }
}
