<?php

namespace App\Services\CkanApi\Repositories;

class UserRepository extends BaseRepository
{
    protected $action_name = 'user';

    /**
     * Return a list of users
     *
     * @link http://docs.ckan.org/en/latest/api/#ckan.logic.action.get.user_list
     * @param array $data
     * @return array
     */
    public function all(array $data = []): array
    {
        $defaults = [
            'all_fields' => true,
            'limit' => $this->per_page,
            'offset' => 0,
        ];

        $data = array_merge($defaults, $data);

        return parent::list($data);
    }
}
