<?php
/**
 * @author    Zoltan Szanto <zoli@prestachamps.com>
 * @copyright Prestachamps
 * @license   MIT
 */

namespace PrestaChamps;

use WebshopappApiClient;
use WebshopappApiException;
use yii\data\BaseDataProvider;
use yii\data\DataProviderInterface;

/**
 * Class LightspeedDataProvider
 * @package app\DataProviders
 */
class LightspeedDataProvider extends BaseDataProvider implements DataProviderInterface
{
    /**
     * @var string The type of entity you want to read. See the api docs.
     *
     * For example products, catalog, categories, etc.
     */
    public $entity;

    /**
     * @var string|callable the column that is used as the key of the data models.
     * This can be either a column name, or a callable that returns the key value of a given data model.
     *
     * If this is not set, the keys of the [[models]] array will be used.
     */
    public $key;

    /**
     * @var string Api server for lightspeed
     */
    public $apiServer;

    /**
     * @var string Api key for lightspeed
     */
    public $apiKey;

    /**
     * @var string User secret for lightspeed
     */
    public $userSecret;

    /**
     * @var string Api lang for lightspeed
     */
    public $apiLanguage = 'en';

    /**
     * @var WebshopappApiClient the api client itself
     */
    public $client;

    public function init()
    {
        parent::init();
        $this->initClient();
    }

    /**
     * @throws WebshopappApiException
     */
    protected function initClient()
    {
        $this->client = new WebshopappApiClient(
            $this->apiServer,
            $this->apiKey,
            $this->userSecret,
            $this->apiLanguage
        );
    }

    /**
     * Returns the total number of data models.
     * When [[getPagination|pagination]] is false, this is the same as [[getCount|count]].
     * @return int total number of possible data models.
     */
    public function getTotalCount()
    {
        if ($this->entity == 'shop') {
            return 1;
        }
        return $this->client->{$this->entity}->count();
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
            $limit = 250;
            $page = 1;
        }

        if ($this->entity == 'shop') {
            return [
                $this->client->{$this->entity}->get(
                    null,
                    [
                        'limit' => $limit,
                        'page'  => $page
                    ])
            ];
        }
        return $this->client->{$this->entity}->get(
            null,
            [
                'limit' => $limit,
                'page'  => $page
            ]);
    }

    /**
     * Prepares the keys associated with the currently available data models.
     * @param array $models the available data models
     * @return array the keys
     */
    protected function prepareKeys($models)
    {
        $keys = [];
        if ($this->key !== null) {
            foreach ($models as $model) {
                if (is_string($this->key)) {
                    $keys[] = $model[$this->key];
                } else {
                    $keys[] = call_user_func($this->key, $model);
                }
            }

            return $keys;
        }

        return array_keys($models);
    }

    /**
     * Returns a value indicating the total number of data models in this data provider.
     * @return int total number of data models in this data provider.
     */
    protected function prepareTotalCount()
    {
        if ($this->entity == 'shop') {
            return 1;
        }
        return $this->client->{$this->entity}->count();
    }
}
