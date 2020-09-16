<?php
/**
 * MailChimp
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Commercial License
 * you can't distribute, modify or sell this code
 *
 * @author    Zoltan Szanto <zoli@prestachamps.com>
 * @copyright Mailchimp
 * @license   commercial
 */

namespace PrestaChamps\Lightspeed\Retail;

use yii\data\BaseDataProvider;
use yii\data\DataProviderInterface;
use ZfrLightspeedRetail\LightspeedRetailClient;

/**
 * Class RetailDataProvider
 *
 * @package PrestaChamps\Lightspeed\Retail
 */
class RetailDataProvider extends BaseDataProvider implements DataProviderInterface
{
    /**
     * @var LightspeedRetailClient
     */
    public $client;

    /**
     * @var int API entity
     */
    public $entity;

    public $timestamp;

    public $relations = [];

    /**
     * Returns a value indicating the total number of data models in this data provider.
     *
     * @return int total number of data models in this data provider.
     */
    protected function prepareTotalCount()
    {
        $method = "get" . ucfirst($this->entity) . "Iterator";

        $count = iterator_count($this->client->$method(['timeStamp' => $this->timestamp]));

        $this->setTotalCount($count);

        return $count;
    }

    /**
     * @return bool
     */
    public function getSort()
    {
        return false;
    }

    /**
     * Prepares the data models that will be made available in the current page.
     *
     * @return array the available data models
     */
    protected function prepareModels()
    {
        $pagination = $this->getPagination();

        if ($pagination !== false) {
            $pagination->totalCount = $this->getTotalCount();
            $limit = $pagination->getLimit();
            $page = $pagination->getPage() + 1;
        } else {
            $limit = 100;
            $page = 1;
        }

        $method = "get" . ucfirst($this->entity);
        $resp = $this->client->$method(
            [
                'limit' => $this->getPagination()->limit,
                'offset' => $this->getPagination()->getOffset(),
                'load_relations' => empty($this->relations) ? 'all' : json_encode($this->relations),
                'timeStamp' => $this->timestamp,
            ]
        )->toArray();
        return $resp;
    }

    /**
     * Prepares the keys associated with the currently available data models.
     *
     * @param array $models the available data models
     *
     * @return array the keys
     */
    protected function prepareKeys($models)
    {
        if (empty($models)) {
            return [];
        }

        [$firstItem] = $models;
        $this->setKeys(array_keys($firstItem));

        return;
    }
}
