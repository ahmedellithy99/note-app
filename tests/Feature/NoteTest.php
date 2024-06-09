<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NoteTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_render_index_view_only_for_authenticated_users(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/notes');

        $response->assertStatus(200);
    }

    public function test_render_create_page_only_for_authenticated_users(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/notes/create');

        $response->assertStatus(200);
    }

    public function test_render_edit_page_only_for_authenticated_users(): void
    {
        $user = User::factory()->create();
        
        $note = Note::factory()->for($user)->create();

        $response = $this->actingAs($user)->get("/notes/$note->id/edit");

        $response->assertStatus(200);
    }

    public function test_store_fucntion(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('notes' ,
        ['title' => 'Alo' ,
        'body' => 'Hello there mate!',
        'user_id' => $user->id]);

        $this->assertDatabaseHas('notes' , ['title' => 'Alo' ,
        'body' => 'Hello there mate!',
        'user_id' => $user->id]);

        $response->assertRedirect('notes');
        $response->assertSessionHas('success', 'Note created successfully.');
    }

    public function test_note_update()
{
    $user = User::factory()->create();
    $note = Note::factory()->for($user)->create();

    $updateData = [
        'title' => 'Updated Note Title',
        'body' => 'Updated Note Body',
    ];

    $response = $this->actingAs($user)->put("/notes/{$note->id}", $updateData);

    $this->assertDatabaseHas('notes', [
        'id' => $note->id,
        'title' => 'Updated Note Title',
        'body' => 'Updated Note Body',
    ]);

    $response->assertRedirect('/notes');
    $response->assertSessionHas('success', 'Note updated successfully.');
}

public function test_note_deletion()
{
    $user = User::factory()->create();
    $note = Note::factory()->for($user)->create();

    $response = $this->actingAs($user)->delete("/notes/{$note->id}");

    $this->assertDatabaseMissing('notes', [
        'id' => $note->id,
    ]);

    $response->assertRedirect('/notes');
    $response->assertSessionHas('success', 'Note deleted successfully.');
}

public function test_unauthorized_user_cannot_access_other_users_notes()
{
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $note = Note::factory()->for($user2)->create();

    $response = $this->actingAs($user1)->get("/notes/{$note->id}/edit");
    $response->assertStatus(403);

    $response = $this->actingAs($user1)->put("/notes/{$note->id}", [
        'title' => 'Unauthorized Update',
        'body' => 'This should not be allowed',
    ]);
    $response->assertStatus(403);

    $response = $this->actingAs($user1)->delete("/notes/{$note->id}");
    $response->assertStatus(403);

}
    
}

