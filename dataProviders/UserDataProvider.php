<?php

namespace krivobokruslan\fayechat\dataProviders;

use krivobokruslan\fayechat\entities\User;
use krivobokruslan\fayechat\converted\User as cUser;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class UserDataProvider extends ActiveDataProvider
{
    protected function prepareModels()
    {
        if (!$this->query instanceof Query) {
            throw new InvalidConfigException('The "query" property must be an instance of a class yii\db\Query or its subclasses.');
        }
        $query = clone $this->query;
        if (($pagination = $this->getPagination()) !== false) {
            $pagination->totalCount = $this->getTotalCount();
            if ($pagination->totalCount === 0) {
                return [];
            }
            $query->limit($pagination->getLimit())->offset($pagination->getOffset());
        }
        if (($sort = $this->getSort()) !== false) {
            $query->addOrderBy($sort->getOrders());
        }

        $result = [];

        foreach ($query->batch(20) as $rows) {
            /** @var User $row */
            foreach ($rows as $row) {
                $result[] = new cUser($row);
            }
        }

        return $result;
    }
}