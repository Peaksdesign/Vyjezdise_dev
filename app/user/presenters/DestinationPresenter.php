<?php


namespace App\User\Presenters;


use App\Repository\Album\AlbumRepository;
use App\Repository\Destination\DestinationRepository;
use App\Repository\Opening\OpeningRepository;
use App\Repository\Slope\SlopeRepository;
use Nette\Application\UI\Presenter;
use Nette\Database\Table\IRow;
use Nette\Utils\ArrayHash;

/**
 * Class DestinationPresenter
 * @package App\User\Presenters
 * @author Josef Banya
 */
class DestinationPresenter extends BasePresenter
{

    /**
     * @param $id
     */
    public function actionDetail($id)
    {
        if (!$id) {
            $this->redirect('Homepage:default');
        }

        $detail = $this->getDestinationRepository()
            ->getList()->where(['id' => $id])->fetch();

        if (!$detail instanceof IRow) {
            $this->redirect('Homepage:default');
        }

        $slopes = $this->getSlopeRepository()
            ->getList()->where(['destination_id' => $detail->id])->fetchAll();

        $openings = $this->getOpeningRepository()
            ->getList()->where(['destination_id' => $detail->id])->fetchAll();

        $openingHours = new ArrayHash();
        foreach ($openings as $opening) {
            $openingHours->{$opening->day} = $opening->body;
        }

        $areal = new ArrayHash();
        $areal->id = $detail->id;
        $areal->name = $detail->title;
        $areal->distance = 0;
        $areal->desc = $detail->description;
        $areal->address = $detail->address;
        $areal->district = $detail->area;
        $areal->contact = $detail->tel;
        $areal->website = $detail->website;
        $areal->skipass = $detail->price;
        $areal->lat = $detail->lat;
        $areal->lng = $detail->lng;

        if (!empty($detail->album_id)) {
            $image = $this->albumRepository->getDatabase()->table('album_images')->where(['album_id' => $detail->album_id, 'is_primary' => 1])->fetch();
            $image = 'upload/' . $image->image->image_file;
        } else {
            $image = 'frontend-assets/img/temp/temp.jpg';
        }

        $areal->imgPath = $image;
        $areal->openingHours = $openingHours;
        $areal->slopes = $slopes;

        $ratingList = $this->getRatingRepository()
            ->getList()
            ->where(['destination_id' => $detail->id]);

        $ratingCount = $ratingList->count('id');

        $ratings = $this->getRatingRepository()
            ->getList()
            ->select('SUM(final) / COUNT(id) AS totalCount,
                                                SUM(parking) / COUNT(id) AS parkingCount,
                                                SUM(food) / COUNT(id) AS foodCount,
                                                SUM(queue) / COUNT(id) AS linesCount')
            ->where(['destination_id' => $detail->id])->fetch();

        $areal->ratings = new ArrayHash();
        $areal->ratings->num = $ratingCount;
        $areal->ratings->total = $ratings->totalCount;
        $areal->ratings->parking = $ratings->parkingCount;
        $areal->ratings->lines = $ratings->linesCount;
        $areal->ratings->food = $ratings->foodCount;

        $this->template->areal = $areal;

        $commentsNum = count($ratingList);

        $bulksNum = $commentsNum % 3 == 0 ? $commentsNum % 3 : ($commentsNum % 3) + 1;

        $this->template->comments = $ratingList;
        $this->template->bulksNum = $bulksNum;

        if ($detail->album_id !== null) {
            $images = $this->getImageRepository()
                ->getDatabase()
                ->table('album_images')
                ->where(['album_id' => $detail->album_id]);

            $imagesCount = $images->count('id');
        } else {
            $images = [];
            $imagesCount = 0;
        }

        $this->template->images = $images;
        $this->template->imagesCount = $imagesCount;

        $ratingControl = $this->getComponent('addRating');
        $ratingControl->setDestinationId($areal->id);
    }


}