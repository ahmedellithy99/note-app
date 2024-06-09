<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NoteApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_notes_api()
    {
        $user = User::factory()->create();
        Note::factory()->for($user)->count(3)->create();

        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/notes');

        $response->assertStatus(200)
                ->assertJsonCount(3);
    }

    public function test_store_new_note_api()
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/notes', [
            'title' => 'New Note',
            'body' => 'This is the body of the new note.',
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'id', 'title', 'body', 'user_id', 'created_at', 'updated_at'
                ]);

        $this->assertDatabaseHas('notes', [
            'title' => 'New Note',
        ]);
    }

    public function test_update_notes_api()
    {
        $user = User::factory()->create();
        $note = Note::factory()->for($user)->create([
            'title' => 'title',
            'body' => 'body body',
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/notes/{$note->id}", [
            'title' => 'Updated Title',
            'body' => 'Updated Body',
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'id' => $note->id,
                    'title' => 'Updated Title',
                    'body' => 'Updated Body',
                ]);

        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'title' => 'Updated Title',
            'body' => 'Updated Body',
        ]);
    }
    
    public function test_delete_note_api()
    {
        $user = User::factory()->create();
        $note = Note::factory()->for($user)->create();

        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/notes/{$note->id}");

        $response->assertStatus(200)
                ->assertJson(['message' => 'Note deleted successfully']);

        $this->assertDatabaseMissing('notes', [
            'id' => $note->id,
        ]);
    }

    public function test_unauthorized_user_cannot_access_others_notes_api()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        
        $note = Note::factory()->for($otherUser)->create();

        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/notes/$note->id" , ['title' => 'hello' , 'body' => 'Hello Hello']);

        $response->assertStatus(403);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/notes/$note->id" );

        $response->assertStatus(403);
    }

}
