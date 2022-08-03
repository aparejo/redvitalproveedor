<?php

namespace App\Traits;

use App\Http\Controllers\Api\WebNotificationController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\WebPushConfig;

trait ApiResponse
{
    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }
    protected function successMessage($data, $code = Response::HTTP_ACCEPTED)
    {
        return response()->json(['data' => $data], $code);
    }
    
    protected function errorResponse($message, $code = Response::HTTP_BAD_REQUEST)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function showAll(Collection $collection, $code = 200)
    {
        return $this->successResponse(['data' => $collection->sortBy('id')->values()->all()], $code);
    }
    
    protected function showAllResources(ResourceCollection $collection, $code = 200)
    {

        return $this->successResponse(['data' => $collection->sortBy('id')->values()->all()], $code);
    }
    protected function showOne(Model $instace, $code = 200)
    {
        return $this->successResponse(['data' => $instace], $code);
    }
    protected function showOneResource(JsonResource $instace, $code = 200)
    {
        return $this->successResponse(['data' => $instace], $code);
    }
    protected function showAllResourcesPaginate(ResourceCollection $collection, $code = 200)
    {
        /* $collection = $this->paginate($collection); */
        return $this->successResponse(['data' => $collection], $code);
    }
    protected function paginate(ResourceCollection $collation)
    {
        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 20;
        $result = $collation->slice(($page - 1) * $perPage, $perPage)->values();
        $paginated = new LengthAwarePaginator($result, $collation->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPage(),
        ]);
        $paginated->appends(request()->all());

        return $paginated;
    }

    /*  protected function listarId(ResourceCollection $collection)
    {
    return $collection->sortBy('id')->values()->all();
    } */

  

}