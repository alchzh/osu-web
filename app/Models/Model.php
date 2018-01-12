<?php

/**
 *    Copyright 2015-2017 ppy Pty. Ltd.
 *
 *    This file is part of osu!web. osu!web is distributed with the hope of
 *    attracting more community contributions to the core ecosystem of osu!.
 *
 *    osu!web is free software: you can redistribute it and/or modify
 *    it under the terms of the Affero GNU General Public License version 3
 *    as published by the Free Software Foundation.
 *
 *    osu!web is distributed WITHOUT ANY WARRANTY; without even the implied
 *    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *    See the GNU Affero General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *    along with osu!web.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace App\Models;

use App\Exceptions\ModelNotSavedException;
use App\Libraries\Transactions\AfterCommit;
use App\Libraries\Transactions\AfterRollback;
use App\Libraries\TransactionState;
use App\Traits\MacroableModel;
use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    use MacroableModel;
    protected $connection = 'mysql';

    public function getMacros()
    {
        $macros = $this->macros ?? [];
        $macros[] = 'realCount';

        return $macros;
    }

    /**
     * Locks the current model for update with `select for update`.
     *
     * @return Model
     */
    public function lockSelf()
    {
        return $this->lockForUpdate()->find($this->getKey());
    }

    public function macroRealCount()
    {
        return function ($baseQuery) {
            $query = clone $baseQuery;
            $query->getQuery()->orders = null;
            $query->getQuery()->offset = null;
            $query->limit(null);

            return $query->count();
        };
    }

    public function scopeOrderByField($query, $field, $ids)
    {
        $size = count($ids);

        if ($size === 0) {
            return;
        }

        $bind = implode(',', array_fill(0, $size, '?'));
        $string = "FIELD({$field}, {$bind})";
        $values = array_map('strval', $ids);

        $query->orderByRaw($string, $values);
    }

    public function scopeNone($query)
    {
        $query->whereRaw('false');
    }

    public function delete()
    {
        $result = parent::delete();
        $this->enlistCallbacks();

        return $result;
    }

    public function save(array $options = [])
    {
        $transaction = resolve('TransactionState')->current($this->connection);
        if ($transaction) {
            if ($this instanceof AfterCommit) {
                $transaction->addCommittable($this);
            }

            if ($this instanceof AfterRollback) {
                $transaction->addRollbackable($this);
            }
        }

        $result = parent::save($options);

        if ($transaction === null) {
            if ($this instanceof AfterCommit && $result === true) {
                $this->afterCommit();
            } elseif ($this instanceof AfterRollback && $result === false) {
                $this->afterRollback();
            }
        }

        return $result;
    }

    public function saveOrExplode($options = [])
    {
        $result = $this->save($options);

        if ($result === false) {
            $message = method_exists($this, 'validationErrors') ?
                implode("\n", $this->validationErrors()->allMessages()) :
                'failed saving model';

            throw new ModelNotSavedException($message);
        }

        return $result;
    }
}
