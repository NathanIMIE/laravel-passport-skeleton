<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

use Illuminate\Auth\AuthenticationException;

use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException as HttpException;

use App\Http\Controllers\Controller;

use App\Ticket;
use App\Comment;

use App\Http\Requests\CreateTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Requests\DeleteTicketRequest;
use App\Http\Requests\AddAssignTicketRequest;
use App\Http\Requests\DeleteAssignTicketRequest;
use App\Http\Requests\StartTicketRequest;
use App\Http\Requests\FinishTicketRequest;
use App\Http\Requests\CommentTicketRequest;
use App\Http\Requests\UncommentTicketRequest;

class TicketsController extends Controller
{
    /**
     * Create ticket
     *
     * @return Response
     */
    public function createTicket(CreateTicketRequest $request)
    {
        $input = $request->validated();

        $user = $request->user();

        $ticket = new Ticket;
        $ticket->title = $input['title'];
        $ticket->description = $input['description'];
        $ticket->priority = $input['priority'];
        $ticket->state = 'PENDING';

        $ticket->proprietaire()->associate($user);

        $ticket->save();
    
        return response($ticket, 201);
    }

    /**
     * Update ticket
     *
     * @return Response
     */
    public function updateTicket(UpdateTicketRequest $request)
    {
        $input = $request->validated();
        $id = $request->route('id');
        $ticket = Ticket::findOrFail($id);
        if(!!$input['description']) $ticket->description = $input['description'];
        if(!!$input['priority']) $ticket->priority = $input['priority'];

        $ticket->save();

        return response($ticket, 200);
    }

    /**
     * Delete ticket
     *
     * @return Response
     */
    public function deleteTicket(DeleteTicketRequest $request)
    {
        $id = $request->route('id');
        $ticket = Ticket::findOrFail($id);

        $ticket->delete();

        return response("Ticket deleted", 204);
    }

    /**
     * Assign a ticket
     *
     * @return Response
     */
    public function addAssignTicket(AddAssignTicketRequest $request)
    {
        $input = $request->validated();
        $id = $request->route('id');
        $ticket = Ticket::findOrFail($id);
        $user = $input['user'];

        $ticket->assignation()->associate($user);
        $ticket->first_assignation = $ticket->first_assignation === null ? now() : $ticket->first_assignation;
        $ticket->last_assignation = now();
        $ticket->state = "WAITING";
        $ticket->save();

        return response($ticket, 200);
    }

    /**
     * Delete a assign
     *
     * @return Response
     */
    public function deleteAssignTicket(DeleteAssignTicketRequest $request)
    {
        $id = $request->route('id');
        $ticket = Ticket::findOrFail($id);

        $ticket->assignation()->associate(null);
        $ticket->last_assignation = now();
        $ticket->state = "PENDING";
        $ticket->save();

        return response($ticket, 200);
    }

    /**
     * Start ticket
     *
     * @return Response
     */
    public function startTicket(StartTicketRequest $request)
    {
        $id = $request->route('id');
        $ticket = Ticket::findOrFail($id);

        $ticket->state = "IN_PROGRESS";
        $ticket->save();

        return response($ticket, 200);
    }

    /**
     * Finish ticket
     *
     * @return Response
     */
    public function finishTicket(FinishTicketRequest $request)
    {
        $id = $request->route('id');
        $ticket = Ticket::findOrFail($id);

        $ticket->state = "DONE";
        $ticket->save();

        return response($ticket, 200);
    }

    /**
     * Comment a ticket
     *
     * @return Response
     */
    public function commentTicket(CommentTicketRequest $request)
    {
        $input = $request->validated();
        $id = $request->route('id');
        $user = Auth::user();

        $ticket = Ticket::findOrFail($id);

        $comment = new Comment;

        $comment->text = $input['text'];
        $comment->ticket()->associate($ticket);
        $user->comments()->save($comment);

        return response($comment, 201);
    }

    /**
     * Uncomment a ticket
     *
     * @return Response
     */
    public function uncommentTicket(UncommentTicketRequest $request)
    {
        $id = $request->route('id');

        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response("Comment delete", 204);
    }

    /**
     * Get Owned Tickets
     *
     * @return Response
     */
    public function getOwnedTickets(Request $request)
    {
        $user = $request->user();
        $ownedTickets = $user->ownedTickets;

        return response($ownedTickets, 200);
    }

    /**
     * Get Assigned Tickets
     *
     * @return Response
     */
    public function getAssignedTickets(Request $request)
    {
        $user = $request->user();
        $assignedTickets = $user->assignedTickets;

        return response($assignedTickets, 200);
    }

    /**
     * Get Ticket
     *
     * @return Response
     */
    public function getTicket(Request $request)
    {
        $id = $request->route('id');
        
        $ticket = Ticket::findOrFail($id);

        return response($ticket, 200);
    }
}