<?php

namespace App\Services\CkanApi\Repositories;

class RevisionRepository extends BaseRepository
{
    protected $action_name = 'revision';

    /**
     * @param array $data
     * @return array
     * @link http://docs.ckan.org/en/latest/api/#ckan.logic.action.get.revision_list
     *
     */
    public function all($data = [])
    {
        $defaults = [];

        $data = array_merge($defaults, $data);

        return parent::list($data);
    }
}
