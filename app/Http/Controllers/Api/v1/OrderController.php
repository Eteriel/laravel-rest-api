<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\OrderResource;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends ApiController
{
    /**
     * @OA\Post(
     *     path="/api/v1/item/add/{order_id}/{item_id}",
     *     summary="Добавить товар в заказ",
     *     tags={"Заказ, Управление товарами"},
     *         @OA\Parameter(
     *         description="Номер заказа",
     *         in="path",
     *         name="order_id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *         @OA\Parameter(
     *         description="Идентификатор товара",
     *         in="path",
     *         name="item_id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="Заказ обновлен",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     example="success"
     *                 ),
     *                 @OA\Property(
     *                     property="message",
     *                     type="string",
     *                     example="Order updated",
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=203,
     *         description="Товар добавлен",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     example="success",
     *                 ),
     *                 @OA\Property(
     *                     property="message",
     *                     type="string",
     *                     example="Item added",
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Добавлять товары разрешено только в заказ со статусом «cоздан»",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     example="error",
     *                 ),
     *                 @OA\Property(
     *                     property="message",
     *                     type="string",
     *                     example="Order id = 1 has «processed» status, but only «created» status allowed",
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Заказ и/или товар не найдены",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="status",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="message",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    /**
     * @param $order
     * @param $item
     * @return \Illuminate\Http\JsonResponse
     */

    public function addItem($order, $item)
    {
        $itemOrder = $order->items->firstWhere('id', $item->id);

        if ($order->status == 'created') {
            if ($itemOrder) {
                $itemOrder->pivot->increment('count');

                return response()->json(['status' => 'success', 'message' => 'Order updated'], 202);
            } else {
                $order->items()->attach($item)->touch();

                return response()->json(['status' => 'success', 'message' => 'Item added'], 203);
            }
        } else {

            return response()->json(['status' => 'error', 'message' => 'Order id = ' . $order->id . ' has «' . $order->status  . '» status, but only «created» status allowed'], 403);
        }

    }

    /**
     * @OA\Delete(
     *     path="/api/v1/item/remove/{order_id}/{item_id}",
     *     summary="Убрать товар из заказа",
     *     tags={"Заказ, Управление товарами"},
     *         @OA\Parameter(
     *         description="Номер заказа",
     *         in="path",
     *         name="order_id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *         @OA\Parameter(
     *         description="Идентификатор товара",
     *         in="path",
     *         name="item_id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="Заказ обновлен",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     example="success"
     *                 ),
     *                 @OA\Property(
     *                     property="message",
     *                     type="string",
     *                     example="Order updated",
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=203,
     *         description="Товар удален",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     example="success",
     *                 ),
     *                 @OA\Property(
     *                     property="message",
     *                     type="string",
     *                     example="Item removed",
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Удалять товары разрешено только с заказов со статусом «cоздан»",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     example="error",
     *                 ),
     *                 @OA\Property(
     *                     property="message",
     *                     type="string",
     *                     example="Order id = 1 has «processed» status, but only «created» status allowed",
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Заказ и/или товар не найдены",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="status",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="message",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    /**
     * @param $order
     * @param $item
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     * @param $order
     * @param $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeItem($order, $item)
    {
        $itemOrder = $order->items->firstWhere('id', $item->id);

        if ($order->status == 'created') {
            if ($itemOrder && $itemOrder->pivot->count > 1) {
                $itemOrder->pivot->decrement('count');

                return response()->json(['status' => 'success', 'message' => 'Order updated'], 202);
            } else {

                $order->items()->detach($item);
                return response()->json(['status' => 'success', 'message' => 'Item removed'], 203);
            }
        } else {

            return response()->json(['error' => 'Order id = ' . $order->id . ' has «' . $order->status  . '» status, but only «created» status allowed'], 403);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/order/create",
     *     summary="Создать заказ",
     *     tags={"Заказ, Управление заказами"},
     *     @OA\Response(
     *         response=201,
     *         description="Заказ создан",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *               @OA\Property(
     *                     property="data",
     *                     type="object",
     *                 @OA\Property(
     *                         property="id",
     *                         type="integer"
     *                  ),
     *                 @OA\Property(
     *                         property="status",
     *                         type="string"
     *                  ),
     *                 @OA\Property(
     *                         property="items",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object"
     *                      )
     *               )
     *            )
     *         )
     *      )
     *   )
     * )
     */

    /**
     * @return OrderResource
     */

    public function createOrder()
    {
        $order = Order::create(['status' => 'created']);
        return OrderResource::make($order);
    }


    /**
     * @OA\Get(
     *     path="/api/v1/order/{order_id}",
     *     summary="Просмотреть заказ",
     *     tags={"Заказ, Управление заказами"},
     *         @OA\Parameter(
     *         description="Номер заказа",
     *         in="path",
     *         name="order_id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Заказ создан",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *               @OA\Property(
     *                     property="data",
     *                     type="object",
     *                 @OA\Property(
     *                         property="id",
     *                         type="integer"
     *                  ),
     *                 @OA\Property(
     *                         property="status",
     *                         type="string"
     *                  ),
     *                 @OA\Property(
     *                         property="items",
     *                         type="array",
     *                         @OA\Items(
     *                             type="object",
     *                              @OA\Property(property="id", type="integer"),
     *                              @OA\Property(property="name", type="string"),
     *                              @OA\Property(property="count", type="integer"),
     *                          )
     *                      )
     *                  )
     *             )
     *         )
     *     )
     * )
     */

    /**
     * @param $order
     * @return OrderResource
     */

    public function showOrder($order)
    {
            return OrderResource::make($order);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/order/{order_id}/process",
     *     summary="Обработать заказ",
     *     tags={"Заказ, Управление заказами"},
     *         @OA\Parameter(
     *         description="Номер заказа",
     *         in="path",
     *         name="order_id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="Статус заказа изменен на «обработан»",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     example="success"
     *                 ),
     *                 @OA\Property(
     *                     property="message",
     *                     type="string",
     *                     example="Status changed to «processed»",
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Статус заказа не может быть изменен",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     example="error"
     *                 ),
     *                 @OA\Property(
     *                     property="message",
     *                     type="string",
     *                     example="Status cannot be changed",
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    /**
     * @param $order
     * @return \Illuminate\Http\JsonResponse
     */

    public function processOrder($order)
    {
        if ($order->status == 'created') {
            $order->update(['status' => 'processed']);

            return response()->json(['status' => 'success', 'message' => 'Status changed to «processed»'], 202);
        } else {

            return response()->json(['status' => 'error', 'message' => 'Status cannot be changed'], 403);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/order/{order_id}/transfer",
     *     summary="Передать курьеру заказ",
     *     tags={"Заказ, Управление заказами"},
     *         @OA\Parameter(
     *         description="Номер заказа",
     *         in="path",
     *         name="order_id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="Статус заказа изменен на «передан курьеру»",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     example="success"
     *                 ),
     *                 @OA\Property(
     *                     property="message",
     *                     type="string",
     *                     example="Status changed to «transferred to the courier»",
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Статус заказа не может быть изменен",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     example="error"
     *                 ),
     *                 @OA\Property(
     *                     property="message",
     *                     type="string",
     *                     example="Status cannot be changed",
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    /**
     * @param $order
     * @return \Illuminate\Http\JsonResponse
     */

    public function transferOrder($order)
    {
        if ($order->status == 'processed') {
            $order->update(['status' => 'transferred']);
            return response()->json(['status' => 'success', 'message' => 'Status changed to «transferred to the courier»'], 202);
        } else {

            return response()->json(['status' => 'error', 'message' => 'Status cannot be changed'], 403);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/order/{order_id}/complete",
     *     summary="Выполнить заказ",
     *     tags={"Заказ, Управление заказами"},
     *         @OA\Parameter(
     *         description="Номер заказа",
     *         in="path",
     *         name="order_id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="Статус заказа изменен на «выполнен»",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     example="success"
     *                 ),
     *                 @OA\Property(
     *                     property="message",
     *                     type="string",
     *                     example="Status changed to «completed»",
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Статус заказа не может быть изменен",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     example="error"
     *                 ),
     *                 @OA\Property(
     *                     property="message",
     *                     type="string",
     *                     example="Status cannot be changed",
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    /**
     * @param $order
     * @return \Illuminate\Http\JsonResponse
     */

    public function completeOrder($order)
    {
        if ($order->status == 'transferred') {
            $order->update(['status' => 'completed']);

            return response()->json(['status' => 'success', 'message' => 'Status changed to «completed»'], 202);
        } else {

            return response()->json(['status' => 'error', 'message' => 'Status cannot be changed'], 403);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/order/{order_id}/cancel",
     *     summary="Отменить заказ",
     *     tags={"Заказ, Управление заказами"},
     *         @OA\Parameter(
     *         description="Номер заказа",
     *         in="path",
     *         name="order_id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="Статус заказа изменен на «отменен»",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     example="success"
     *                 ),
     *                 @OA\Property(
     *                     property="message",
     *                     type="string",
     *                     example="Status changed to «canceled»",
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Статус заказа не может быть изменен",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="status",
     *                     type="string",
     *                     example="error"
     *                 ),
     *                 @OA\Property(
     *                     property="message",
     *                     type="string",
     *                     example="Status cannot be changed",
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    /**
     * @param $order
     * @return \Illuminate\Http\JsonResponse
     */

    public function cancelOrder($order)
    {
        if (in_array($order->status, ['created', 'proceed', 'transferred'])) {
            $order->update(['status' => 'canceled']);

            return response()->json(['success' => 'Status changed to «canceled»'], 202);
        } else {

            return response()->json(['error' => 'Status cannot be changed'], 403);
        }
    }
}
