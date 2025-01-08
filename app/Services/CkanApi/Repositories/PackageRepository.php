<?php

namespace App\Services\CkanApi\Repositories;

class PackageRepository extends BaseRepository
{
    protected $action_name = 'package';

    /**
     * Create a new resource
     * @param array $data
     * @return array
     */
    public function create(array $data = [])
    {
        $this->setActionUri(__FUNCTION__);

        $response = $this->client->post($this->uri, [
            'multipart' => $this->dataToMultipart($data),
        ]);

        return $this->responseToJson($response);
    }

    /**
     * Revise a new resource
     * @param array $data
     * @return mixed
     */
    public function revise(array $data = [])
    {
        $this->setActionUri(__FUNCTION__);

        $response = $this->client->post($this->uri, [
            'multipart' => $this->dataToMultipart($data),
        ]);

        return $this->responseToJson($response);
    }
}
