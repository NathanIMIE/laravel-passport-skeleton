<?php

namespace Tests\Feature;

use Tests\TestCase;

use Tests\Concerns\InteractsWithSessionAPI;

class ApiControllerTest extends TestCase
{
    use InteractsWithSessionAPI;

    private static $idTicket = null;
    private static $idComment = null;

    public function testNoAuthentification() 
    {
        $response = $this->json('POST', '/api/ticket', [
            "title" => "Titre du ticket",
            "description" => "Description du ticket",
            "priority" => "basse"
        ], []);

        $response->assertStatus(401);
    }

    /**
     * @depends testNoAuthentification
     */
    public function testUnavailableTicket()
    {
        $this->login();

        if(self::$session) {

            // Test création avec des datas invalides
            $response = $this->json('POST', '/api/ticket', [
                "title" => "Titre du ticket",
                "description" => "Description du ticket",
                "priority" => " Je ne peux mettre que basse, normale ou haute ici"
            ], [
                'Authorization' => 'Bearer ' . self::$session->access_token,
            ]);

            $response->assertStatus(422);
        }
    }

    /**
     * @depends testUnavailableTicket
     */
    public function testCreateTicket()
    {
        // Test création ticket avec toutes les datas
        $response = $this->json('POST', '/api/ticket', [
            "title" => "Titre du ticket",
            "description" => "Description du ticket",
            "priority" => " basse"
        ], [
            'Authorization' => 'Bearer ' . self::$session->access_token,
        ]);

        $response->assertStatus(201);

        $ticket = json_decode($response->content());
        self::$idTicket = $ticket->id;
    }

    /**
     * @depends testCreateTicket
     */
    public function testUpdateTicket()
    {
        // Test modification avec toutes les datas
        $response = $this->json('PUT', '/api/ticket/'.self::$idTicket, [
            "description" => "Nouvelle description du ticket",
            "priority" => "normale"
        ], [
            'Authorization' => 'Bearer ' . self::$session->access_token,
        ]);

        $response->assertStatus(200);
    }

    /**
     * @depends testUpdateTicket
     */
    public function testAssignTicker()
    {
        // Test assignation du ticket
        $response = $this->json('PUT', '/api/ticket/assign/'.self::$idTicket, [
            "user" => self::$user->id
        ], [
            'Authorization' => 'Bearer ' . self::$session->access_token,
        ]);

        $response->assertStatus(200);
    }

    /**
     * @depends testAssignTicker
     */
    public function testStartTicket()
    {
        // Test commencer le ticket
        $response = $this->json('PUT', '/api/ticket/start/'.self::$idTicket, [], [
            'Authorization' => 'Bearer ' . self::$session->access_token,
        ]);

        $response->assertStatus(200);
    }

    /**
     * @depends testStartTicket
     */
    public function testStopTicket()
    {
        // Test terminer le ticket
        $response = $this->json('PUT', '/api/ticket/finish/'.self::$idTicket, [], [
            'Authorization' => 'Bearer ' . self::$session->access_token,
        ]);

        $response->assertStatus(200);
    }

    /**
     * @depends testStopTicket
     */
    public function testCommentTicket()
    {
        // Commenter le ticket
        $response = $this->json('POST', '/api/ticket/comment/'.self::$idTicket, [
            'text' => 'Texte du commentaire'
        ], [
            'Authorization' => 'Bearer ' . self::$session->access_token,
        ]);

        $response->assertStatus(201);

        $comment = json_decode($response->content());
        self::$idComment = $comment->id;
    }

    /**
     * @depends testCommentTicket
     */
    public function testUncommentTicket()
    {
        // Decommenter le ticket
        $response = $this->json('DELETE', '/api/ticket/comment/'.self::$idComment, [], [
            'Authorization' => 'Bearer ' . self::$session->access_token,
        ]);

        $response->assertStatus(204);
    }

    /**
     * @depends testUncommentTicket
     */
    public function testUnassignTicket()
    {
        // Test enlever assignation du ticket
        $response = $this->json('DELETE', '/api/ticket/assign/'.self::$idTicket, [], [
            'Authorization' => 'Bearer ' . self::$session->access_token,
        ]);
        
        $response->assertStatus(200);
    }

    /**
     * @depends testUnassignTicket
     */
    public function testGetTicket()
    {
        // Test suppression du ticket
        $response = $this->json('GET', '/api/ticket/'.self::$idTicket, [], [
            'Authorization' => 'Bearer ' . self::$session->access_token,
        ]);

        $response->assertStatus(200);
        
    }
 
    /**
     * @depends testUnassignTicket
     */
    public function testDeleteTicket()
    {
        // Test suppression du ticket
        $response = $this->json('DELETE', '/api/ticket/'.self::$idTicket, [], [
            'Authorization' => 'Bearer ' . self::$session->access_token,
        ]);

        $response->assertStatus(204);
        
    }

    /**
     * @depends testDeleteTicket
     */
    public function testListOwnedTickets()
    {
        // Test suppression du ticket
        $response = $this->json('GET', '/api/ownedTickets', [], [
            'Authorization' => 'Bearer ' . self::$session->access_token,
        ]);

        $response->assertStatus(200);
        
    }

    /**
     * @depends testDeleteTicket
     */
    public function testListAssignedTickets()
    {
        // Test suppression du ticket
        $response = $this->json('GET', '/api/assignedTickets', [], [
            'Authorization' => 'Bearer ' . self::$session->access_token,
        ]);

        $response->assertStatus(200);
        
        $this->logout();
    }
}
