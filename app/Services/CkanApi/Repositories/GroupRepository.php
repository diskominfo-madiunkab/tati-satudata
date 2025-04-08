<?php

namespace App\Services\CkanApi\Repositories;

class GroupRepository extends BaseRepository
{
    protected $action_name = 'group';

    /**
     * @param array $data
     * @return array
     * @link http://docs.ckan.org/en/latest/api/#ckan.logic.action.get.group_list
     *
     */
    public function all($data = [])
    {
        $defaults = [
            // 'limit' => 1000,
            // 'offset' => 0,
            'all_fields' => true
        ];

        $data = array_merge($defaults, $data);

        return parent::list($data);
    }
}
