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

    /**
     * Returns a value indicating the total number of data models in this data provider.
     *
     * @return int total number of data models in this data provider.
     */
    protected function prepareTotalCount()
    {
        $method = "get" . ucfirst($this->entity) . "Iterator";
        return iterator_count($this->client->$method());
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
        $method = "get" . ucfirst($this->entity);
        $resp = $this->client->$method(
            [
                'limit' => $this->getPagination()->limit,
                'offset' => $this->getPagination()->getOffset(),
                'load_relations' => 'all'
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
        list($firstItem) = $models;

        return array_keys($firstItem);
    }
}
