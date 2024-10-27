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
    private $executionUser = '';

    /**
     * @param $supplierId
     * @param $restaurantId
     * @param $apiUsername
     * @param $apiPassword
     * @throws Exception
     */
    public function __construct($supplierId = null, $restaurantId = null, $apiUsername = null, $apiPassword = null, $executionUser = null)
    {
        if (is_null($supplierId) || is_null($restaurantId) || is_null($apiUsername) || is_null($apiPassword) || is_null($executionUser)) {
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
     * @throws Exception
     */
    private function makeRequest($method, $endpoint, $data = null, $returnBoolean = false)
    {
        try {
            $ch = curl_init();
            $url = $this->baseUrl . $this->supplierId . $endpoint;

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $headers = [
                'Authorization: Basic ' . base64_encode($this->apiUsername . ':' . $this->apiPassword),
                'Content-Type: application/json',
                'x-agentname:' . $this->supplierId . " - SelfIntegration",
                'x-executor-user:' . $this->executionUser
            ];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_USERAGENT, $this->supplierId . ' - TrendyolSoft');
            if ($method === 'POST' || $method === 'PUT') {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
            $response = curl_exec($ch);
            if ($returnBoolean) {
                $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                return $statusCode == 200;
            }
            curl_close($ch);
            return json_decode($response, true);
        } catch (Exception $e) {
            throw new Exception(json_encode(["message" => $e->getMessage(), "error" => true]));
        }
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getMenu()
    {
        return $this->makeRequest('GET', '/restaurants/' . $this->restaurantId . '/products');
    }

    /**
     * @param $status
     * @return mixed
     * @throws Exception
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
     * @throws Exception
     */
    public function updateProductStatus($productId, $status)
    {
        $data = ['status' => $status];
        return $this->makeRequest('PUT', '/restaurants/' . $this->restaurantId . '/products/' . $productId . '/status', $data);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getRestaurantInfo()
    {
        return $this->makeRequest('GET', '/restaurants');
    }

    /**
     * @param $areas
     * @return mixed
     * @throws Exception
     */
    public function updateDeliveryAreas($areas)
    {
        return $this->makeRequest('PUT', '/restaurants/' . $this->restaurantId . '/delivery-areas', ['areas' => $areas],true);
    }

    /**
     * @param $workingHours
     * @return mixed
     * @throws Exception
     */
    public function updateWorkingHours($workingHours)
    {
        return $this->makeRequest('PUT', '/restaurants/' . $this->restaurantId . '/working-hours', ['workingHours' => $workingHours],true);
    }

    /**
     * @param $status
     * @return mixed
     * @throws Exception
     */
    public function updateRestaurantStatus($status)
    {
        $data = ['status' => $status];
        return $this->makeRequest('PUT', '/restaurants/' . $this->restaurantId . '/status', $data, true);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getPackages()
    {
        return $this->makeRequest('GET', '/packages?storeId=' . $this->restaurantId);
    }

    /**
     * @param $packageId
     * @param $preparationTime
     * @return mixed
     * @throws Exception
     */
    public function acceptOrder($packageId, $preparationTime)
    {
        $data = ['packageId' => $packageId, 'preparationTime' => $preparationTime];
        return $this->makeRequest('PUT', '/packages/picked', $data, true);
    }

    /**
     * @param $packageId
     * @return mixed
     * @throws Exception
     */
    public function completeOrder($packageId)
    {
        $data = ['packageId' => $packageId];
        return $this->makeRequest('PUT', '/packages/invoiced', $data, true);
    }

    /**
     * @param $packageId
     * @return mixed
     * @throws Exception
     */
    public function shipOrder($packageId)
    {
        $data = ['packageId' => $packageId];
        return $this->makeRequest('PUT', '/packages/' . $packageId . '/manual-shipped', $data,true);
    }

    /**
     * @param $packageId
     * @return mixed
     * @throws Exception
     */
    public function deliverOrder($packageId)
    {
        $data = ['packageId' => $packageId];
        return $this->makeRequest('PUT', '/packages/' . $packageId . '/manual-delivered', $data,true);
    }

    /**
     * @param $packageId
     * @param $itemIdList
     * @param $reasonId
     * @return mixed
     * @throws Exception
     */
    public function cancelOrder($packageId, $itemIdList, $reasonId)
    {
        $data = ['packageId' => $packageId, 'itemIdList' => $itemIdList, 'reasonId' => $reasonId];
        return $this->makeRequest('PUT', '/packages/unsupplied', $data,true);
    }
}
