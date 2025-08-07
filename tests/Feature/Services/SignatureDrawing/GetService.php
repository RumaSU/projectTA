<?php

namespace Tests\Feature\Services\SignatureDrawing;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Users\User;
use App\Models\Signatures\Signature;

use App\Services\SignatureDrawings\GetService as TestGetService;


class GetService extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    
    public function test_get_signatures_for_specific_user() {
        $user = User::first();
        
        $service = new TestGetService($user->getKey());
        
        // Default true
        $defaultSignatures = $service->get(true)->get();
        $this->assertCount(1, $defaultSignatures);

        // Default false
        $nonDefaultSignatures = $service->get(false)->get();
        $this->assertCount(2, $nonDefaultSignatures);
    }
    
    public function test_get_signatures_for_all_users() {
        $service = new TestGetService();
        
        $defaultSignatures = $service->get(true)->get();
        $this->assertCount(1, $defaultSignatures);

        // Default false
        $nonDefaultSignatures = $service->get(false)->get();
        $this->assertCount(2, $nonDefaultSignatures);
    }
}
