<?php

namespace app\models\search;

use app\components\HelperFunctions;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\database\Calendar as CalendarModel;

/**
 * Calendar represents the model behind the search form about `app\models\database\Calendar`.
 */
class Calendar extends CalendarModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type_id', 'activity_id', 'user_id', 'customer_id', 'private'], 'integer'],
            [['date', 'hour_from', 'hour_to', 'description'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $daysObj = null)
    {
//        HelperFunctions::output($daysObj);
        $query = CalendarModel::find();

        $query->with(['activity', 'customer', 'type', 'user']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date' => $this->date,
            'type_id' => $this->type_id,
            'activity_id' => $this->activity_id,
            'user_id' => $this->user_id,
            'customer_id' => $this->customer_id,
            'private' => $this->private,
        ]);

        $query->andFilterWhere(['like', 'hour_from', $this->hour_from])
            ->andFilterWhere(['like', 'hour_to', $this->hour_to])
            ->andFilterWhere(['like', 'description', $this->description]);

        if ($daysObj != null) {
//            HelperFunctions::output($daysObj);
            if ($daysObj->veterinario != "") {
                $query->andFilterWhere(['=', 'user_id', $daysObj->veterinario]);
            }
            if ($daysObj->from_date != "" || $daysObj->to_date != "") {
                $query->andFilterWhere(['between', 'date', $daysObj->from_date, $daysObj->to_date]);
            } else {
                $noOfDays = $daysObj->days;
                $todaysData = date("Y-m-d");
                $endDate = date('Y-m-d', strtotime("+$noOfDays day"));
                $query->andFilterWhere(['between', 'date', $todaysData, $endDate]);
            }

            if ($daysObj->private == 0)
                $query->andWhere("private = $daysObj->private OR private IS NULL");
            else
                $query->andWhere("private = $daysObj->private");
        }

        return $dataProvider;
    }
}
