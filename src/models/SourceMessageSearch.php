<?php

namespace umbalaconmeogia\i18nui\models;

use umbalaconmeogia\i18nui\helpers\HModule;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SourceMessageSearch represents the model behind the search form of `common\models\SourceMessage`.
 */
class SourceMessageSearch extends SourceMessage
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['category', 'message'], 'safe'],
            ['languages', 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
    public function search($params)
    {
        $query = SourceMessage::find();
        $languages = HModule::languages();
        foreach ($languages as $language){
            $query->leftJoin('{{%message}} as '.$language, $language.'.id = {{%source_message}}.id and '.$language.'.language = \''.$language.'\'');
        }

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
        ]);

        $query->andFilterWhere(['ilike', 'category', $this->category])
            ->andFilterWhere(['ilike', 'message', $this->message]);
        foreach ($languages as $language) {
            if(isset($this->languages[$language])) {
                $query->andFilterWhere(['like', "{$language}.translation", $this->languages[$language]]);
            }
        }

        return $dataProvider;
    }
}
