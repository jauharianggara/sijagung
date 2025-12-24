<?php
namespace backend\components;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
/**
 * Class Menu
 * Theme menu widget.
 */
class GridView extends \yii\grid\GridView
{
    public $layout = "{items}\n
    <div class='row'>
      <div class='col-sm-12 col-md-4'>
        {summary}
      </div>

      <div class='col-sm-12 col-md-8'>
        <div class='float-right'>
          {pager}
        </div>
      </div>
    </div>";

    public function renderSummary()
    {
        $count = $this->dataProvider->getCount();
        if ($count <= 0) {
            return '';
        }
        $summaryOptions = $this->summaryOptions;
        $tag = ArrayHelper::remove($summaryOptions, 'tag', 'div');
        if (($pagination = $this->dataProvider->getPagination()) !== false) {
            $totalCount = $this->dataProvider->getTotalCount();
            $begin = $pagination->getPage() * $pagination->pageSize + 1;
            $end = $begin + $count - 1;
            if ($begin > $end) {
                $begin = $end;
            }
            $page = $pagination->getPage() + 1;
            $pageCount = $pagination->pageCount;
            if (($summaryContent = $this->summary) === null) {
                return Html::tag($tag, Yii::t('yii', 'Menampilkan <b>{begin, number}-{end, number}</b> dari <b>{totalCount, number}</b> {totalCount, plural, one{baris} other{baris}}.', [
                        'begin' => $begin,
                        'end' => $end,
                        'count' => $count,
                        'totalCount' => $totalCount,
                        'page' => $page,
                        'pageCount' => $pageCount,
                    ]), $summaryOptions);
            }
        } else {
            $begin = $page = $pageCount = 1;
            $end = $totalCount = $count;
            if (($summaryContent = $this->summary) === null) {
                return Html::tag($tag, Yii::t('yii', 'Total <b>{count, number}</b> {count, plural, one{baris} other{baris}}.', [
                    'begin' => $begin,
                    'end' => $end,
                    'count' => $count,
                    'totalCount' => $totalCount,
                    'page' => $page,
                    'pageCount' => $pageCount,
                ]), $summaryOptions);
            }
        }

        if ($summaryContent === '') {
            return '';
        }

        return Html::tag($tag, Yii::$app->getI18n()->format($summaryContent, [
            'begin' => $begin,
            'end' => $end,
            'count' => $count,
            'totalCount' => $totalCount,
            'page' => $page,
            'pageCount' => $pageCount,
        ], Yii::$app->language), $summaryOptions);
    }
}
