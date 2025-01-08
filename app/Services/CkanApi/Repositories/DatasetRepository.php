<?php

namespace App\Services\CkanApi\Repositories;

/**
 * Class DatasetRepository
 * @package Germanazo\CkanApi\Repositories
 */
class DatasetRepository extends PackageRepository
{
    protected $action_name = 'package';

    /**
     * @param array $data
     * @return array
     * @link http://docs.ckan.org/en/latest/api/#ckan.logic.action.get.package_search
     *
     */
    public function all(array $data = []): array
    {
        $defaults = [
            'include_private' => true,
            'rows' => $this->per_page,
            'start' => 0,
        ];

        $data = array_merge($defaults, $data);

        return parent::search($data) ?? [];
    }

    /**
     * Return a dataset (package)’s revisions as a list of dictionaries.
     *
     * @param string $id
     * @return array
     * @link http://docs.ckan.org/en/latest/api/#ckan.logic.action.get.package_revision_list
     *
     */
    public function revision_list(string $id): array
    {
        return $this->query(__FUNCTION__, ['id' => $id]);
    }
}
