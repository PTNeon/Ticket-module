<?php

namespace Modules\Ticket\Http\Controllers\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Neon\Http\Repositories\Repository;
use Neon\App\Controllers\Controller;
use Neon\App\Helpers\Sms;
use Modules\Ticket\Entities\Ticket;
use Modules\Ticket\Entities\SubTicket;
use Modules\Ticket\Http\Requests\V1\SubTicketStoreRequest;
use Modules\Ticket\Http\Requests\V1\SubTicketUpdateRequest;
use Modules\Ticket\Http\Resources\V1\Ticket as TicketResource;


class SubTicketController extends Controller
{

    protected $model;

    public function __construct(SubTicket $ticket)
    {
        $this->model = new Repository($ticket);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection ::Collection
     */
    public function index(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SubTicketStoreRequest $request
     * @return TicketResource
     */
    public function store(SubTicketStoreRequest $request)
    {
        $user = $request->user();
        $request->merge(['user_id' => $user->id]);
        $this->model->create($request->all());

        if($user->is_admin == 1){
            $ticket = Ticket::find($request->ticket_id);
            $mobile = $ticket->user->mobile;
            $alias = $ticket->user->first_name . ' ' . $ticket->user->last_name;
            if(!empty($mobile)){
                Sms::sendVerify($mobile, $alias , 'ticket');
            }
        }
        return new TicketResource($this->model->show($request->ticket_id));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return TicketResource
     */
    public function show($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param SubTicketUpdateRequest $request
     * @param int $id
     * @return TicketResource
     */
    public function update(SubTicketUpdateRequest $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return TicketResource
     */
    public function destroy($id)
    {
        $this->model->delete($id);
        return new TicketResource($this->model->show($id, [], true));
    }
}
