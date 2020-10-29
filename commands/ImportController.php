<?php
namespace umbalaconmeogia\i18nui\commands;

use batsg\helpers\CsvWithHeader;
use batsg\models\BaseModel;
use umbalaconmeogia\i18nui\models\SourceMessage;
use umbalaconmeogia\i18nui\models\Message;
use yii\console\Controller;

/**
 * DefaultController implements the CRUD actions for SourceMessage model.
 */
class ImportController extends Controller
{
    /**
     * Syntax:
     *   php yii i18nui/import/csv i18n.csv
     * @param string $csvFile Header keys are category, message, languages.
     */
    public function actionCsv($csvFile)
    {
        CsvWithHeader::read($csvFile, function(CsvWithHeader $csv) {
            while ($csv->loadRow() !== FALSE) {
                // Get attributes as an array.
                $attr = $csv->getRowAsAttributes();
                $sourceMessage = SourceMessage::findOneCreateNew([
                    'category' => $attr['category'],
                    'message' => $attr['message'],
                ]);
                BaseModel::saveThrowErrorModel($sourceMessage);

                $csvHeader = $csv->getHeader();
                $countHeader = count($csvHeader);
                for ($i = 2; $i < $countHeader; $i++) {
                    $language = $csvHeader[$i];
                    $message = Message::findOneCreateNew([
                        'id' => $sourceMessage->id,
                        'language' => $language,
                    ]);
                    $message->translation = $attr[$language];
                    BaseModel::saveThrowErrorModel($message);
                }
            }
        });
        echo "DONE.\n";
    }
}