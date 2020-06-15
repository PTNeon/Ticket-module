<?php

namespace Modules\Ticket\Http\Controllers\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Neon\App\Helpers\Upload;
use Neon\Http\Repositories\Repository;
use Neon\App\Controllers\Controller;
use Modules\Ticket\Entities\Ticket;
use Modules\Ticket\Entities\SubTicket;
use Modules\Ticket\Http\Requests\V1\TicketStoreRequest;
use Modules\Ticket\Http\Requests\V1\TicketUpdateRequest;
use Modules\Ticket\Http\Resources\V1\Ticket as TicketResource;
use Modules\Ticket\Http\Resources\V1\SubTicket as SubTicketResource;


class TicketController extends Controller
{

    protected $model;

    public function __construct(Ticket $ticket)
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
        $Auth = false;
        $user = $request->user();
        ($user->is_admin == 0) ? $Auth=true : $Auth=false;
        $relations = ['user','subtickets'];
        $ticket = $request->has('page')
            ? $this->model->pagination($relations,false,$Auth)
            : $this->model->all($relations,false,$Auth);
        return Ticket::collection($ticket);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TicketStoreRequest $request
     * @return TicketResource
     */
    public function store(TicketStoreRequest $request)
    {
        $request->merge(['user_id' => $request->user()->id]);
        $ticket = $this->model->create($request->all());
        $sub = new SubTicket();
        $sub->user_id = $request->user()->id;
        $sub->ticket_id = $ticket->id;
        $sub->text = $request->text;
        $sub->save();

        $subTicketUpdate = SubTicket::find($sub->id);
        if ($request->has('files')) {
            $files = Upload::saveFile($request->file('files'), 'tickets/files', $subTicketUpdate->id);
            if (!is_null($subTicketUpdate->files)) {
                $filesList = !is_null($files) ? array_merge($files, $subTicketUpdate->attachments) : $subTicketUpdate->attachments;
            } else {
                $filesList = $files;
            }
            $subTicketUpdate->attachments = $filesList;
            $subTicketUpdate->update();
        }
        return new TicketResource($this->model->show($ticket->id));
    }

    public function ChangeStateTicket(Request $request)
    {
        $value=0;
        $ticket = Ticket::find($request->ticket_id);
        ($ticket->state == 1) ? $value=0 : $value=1;
        $ticket->state=$value;
        $ticket->save();

        return response()->json([
            'data' =>[
                'status' => 200,
                'message' => 'با موفقیت انجام شد'
            ]
        ]);
    }

    public function getImporanceList(){
        $list = response()->json([
            'data' =>[
                'normal' => 'عادی',
                'high' => 'زیاد'
            ]
        ]);
        return $list;
    }

    public function getSubjectList(){
        $list = response()->json([
            'data' =>[
                'offer' => 'انتقاد',
                'censure' => 'پیشنهاد',
                'accountProblem' => 'مشکل در حساب کاربری'
            ]
        ]);
        return $list;
    }

    public function getStatusList(){
        $list = response()->json([
            'data' =>[
                'waiting' => 'در انتظار بررسی',
                'adminResp' => 'پاسخ اپراتور',
                'userResp' => 'پاسخ کاربر'
            ]
        ]);
        return $list;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return TicketResource
     */
    public function show($id)
    {
        return SubTicketResource::collection(SubTicket::where('ticket_id',$id)->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TicketUpdateRequest $request
     * @param int $id
     * @return TicketResource
     */
    public function update(TicketUpdateRequest $request, $id)
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
