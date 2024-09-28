<?php

namespace furkanmeclis\Tools;

use Exception;

class TrendyolYemekApi
{
    private $supplierId;
    private $restaurantId;
    private $apiUsername;
    private $apiPassword;
    private $baseUrl = 'https://api.trendyol.com/mealgw/suppliers/';

    /**
     * @param $supplierId
     * @param $restaurantId
     * @param $apiUsername
     * @param $apiPassword
     * @throws Exception
     */
    public function __construct($supplierId = null, $restaurantId = null, $apiUsername = null, $apiPassword = null)
    {
        if (is_null($supplierId) || is_null($restaurantId) || is_null($apiUsername) || is_null($apiPassword)) {
            throw new Exception(json_encode(["message" => "Kimlik Bilgileri Girilmedi", "error" => true]));
        }

        $this->supplierId = $supplierId;
        $this->restaurantId = $restaurantId;
        $this->apiUsername = $apiUsername;
        $this->apiPassword = $apiPassword;
    }


    /**
     * @param $method
     * @param $endpoint
     * @param $data
     * @return mixed
     */
    private function makeRequest($method, $endpoint, $data = null)
    {
        $ch = curl_init();
        $url = $this->baseUrl . $this->supplierId . $endpoint;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headers = [
            'Authorization: Basic ' . base64_encode($this->apiUsername . ':' . $this->apiPassword),
            'Content-Type: application/json',
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($method === 'POST' || $method === 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

    /**
     * @return mixed
     */
    public function getMenu()
    {
        return $this->makeRequest('GET', '/restaurants/' . $this->restaurantId . '/products');
    }

    /**
     * @param $status
     * @return mixed
     */
    public function updateCategoryStatus($status)
    {
        $data = ['status' => $status];
        return $this->makeRequest('PUT', '/restaurants/' . $this->restaurantId . '/sections/Trendyol Yemekler/status', $data);
    }

    /**
     * @param $productId
     * @param $status
     * @return mixed
     */
    public function updateProductStatus($productId, $status)
    {
        $data = ['status' => $status];
        return $this->makeRequest('PUT', '/restaurants/' . $this->restaurantId . '/products/' . $productId . '/status', $data);
    }

    /**
     * @return mixed
     */
    public function getRestaurantInfo()
    {
        return $this->makeRequest('GET', '/restaurants');
    }

    /**
     * @param $areas
     * @return mixed
     */
    public function updateDeliveryAreas($areas)
    {
        return $this->makeRequest('PUT', '/restaurants/' . $this->restaurantId . '/delivery-areas', ['areas' => $areas]);
    }

    /**
     * @param $workingHours
     * @return mixed
     */
    public function updateWorkingHours($workingHours)
    {
        return $this->makeRequest('PUT', '/restaurants/' . $this->restaurantId . '/working-hours', ['workingHours' => $workingHours]);
    }

    /**
     * @param $status
     * @return mixed
     */
    public function updateRestaurantStatus($status)
    {
        $data = ['status' => $status];
        return $this->makeRequest('PUT', '/restaurants/' . $this->restaurantId . '/status', $data);
    }

    /**
     * @return mixed
     */
    public function getPackages()
    {
        return $this->makeRequest('GET', '/packages?storeId=' . $this->restaurantId);
    }

    /**
     * @param $packageId
     * @param $preparationTime
     * @return mixed
     */
    public function acceptOrder($packageId, $preparationTime)
    {
        $data = ['packageId' => $packageId, 'preparationTime' => $preparationTime];
        return $this->makeRequest('PUT', '/packages/picked', $data);
    }

    /**
     * @param $packageId
     * @return mixed
     */
    public function completeOrder($packageId)
    {
        $data = ['packageId' => $packageId];
        return $this->makeRequest('PUT', '/packages/invoiced', $data);
    }

    /**
     * @param $packageId
     * @return mixed
     */
    public function shipOrder($packageId)
    {
        $data = ['packageId' => $packageId];
        return $this->makeRequest('PUT', '/packages/' . $packageId . '/manual-shipped', $data);
    }

    /**
     * @param $packageId
     * @return mixed
     */
    public function deliverOrder($packageId)
    {
        $data = ['packageId' => $packageId];
        return $this->makeRequest('PUT', '/packages/' . $packageId . '/manual-delivered', $data);
    }

    /**
     * @param $packageId
     * @param $itemIdList
     * @param $reasonId
     * @return mixed
     */
    public function cancelOrder($packageId, $itemIdList, $reasonId)
    {
        $data = ['packageId' => $packageId, 'itemIdList' => $itemIdList, 'reasonId' => $reasonId];
        return $this->makeRequest('PUT', '/packages/unsupplied', $data);
    }
}
