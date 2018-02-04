<?php


namespace App\Admin\Controls\Image\Form;


use App\Admin\Controls\Base\BaseControl;
use App\Repository\Album\AlbumRepository;
use App\Repository\Image\ImageRepository;
use Nette\Application\UI\Form;
use Nette\Database\DriverException;

/**
 * Class FormControl
 * @package App\Admin\Controls\Image\Form
 * @author Josef Banya
 */
class FormControl extends BaseControl
{
    /** @var string  */
    public $templateFile = 'form';

    /** @var null | int */
    public $albumId = null;

    /** @var  AlbumRepository */
    public $albumRepository;

    /** @var  ImageRepository */
    public $imageRepository;

    /**
     * FormControl constructor.
     * @param AlbumRepository $albumRepository
     * @param ImageRepository $imageRepository
     */
    public function __construct(AlbumRepository $albumRepository, ImageRepository $imageRepository)
    {
        parent::__construct();
        $this->setAlbumRepository($albumRepository)
            ->setImageRepository($imageRepository);
    }


    /**
     * Render template
     */
    public function render()
    {
        parent::render();
        $this->getTemplate()->render();
    }

    /**
     * @return \Nette\Application\UI\Form
     */
    public function createComponentForm()
    {
        $form = new Form();
        $form->addMultiUpload('images', 'Obrázky')->setRequired();
        $form->addSubmit('submit', 'Nahrát');
        $form->onSuccess[] = [$this, 'process'];
        return $form;
    }

    /**
     * @param \Nette\Application\UI\Form $form
     * @throws \Exception
     */
    public function process(Form $form)
    {
        if ($this->getAlbumId() === null) throw new \Exception('album is not defined');
        $values = $form->getValues();

        try {
            $this->getImageRepository()->getDatabase()->beginTransaction();
            /** @var \Nette\Http\FileUpload $image */
            foreach ($values->images as $image) {
                $uploadPath = __DIR__ . '/../../../../../www/upload';
                $imageName = $image->getName();
                $path = $uploadPath . '/';
                $dbPath =  rand(1,929). '/' . $imageName;
                $image->move($path . $dbPath);
                $imageRow = $this->getImageRepository()->insert([
                    'image_file' => $dbPath,
                    'create_date' => new \DateTime()
                ], true);
                $this->getAlbumRepository()->getDatabase()
                    ->table('album_images')->insert([
                        'album_id' => $this->getAlbumId(),
                        'image_id' => $imageRow->id,
                        'is_primary' => 0
                    ]);
            }
            $this->getImageRepository()->getDatabase()->commit();
            $this->getPresenter()->flashMessage('Obrázky uloženy', 'success');
        } catch (\Exception $e) {
            $this->getImageRepository()->getDatabase()->rollBack();
            $this->getPresenter()->flashMessage('Obrázky se nepodařilo uložit', 'error');
        }

        $this->getPresenter()->redirect('this');
    }

    /**
     * @return int|null
     */
    public function getAlbumId()
    {
        return $this->albumId;
    }

    /**
     * @param int|null $albumId
     * @return self Provides Fluent Interface
     */
    public function setAlbumId($albumId)
    {
        $this->albumId = $albumId;
        return $this;
    }

    /**
     * @return AlbumRepository
     */
    public function getAlbumRepository()
    {
        return $this->albumRepository;
    }

    /**
     * @param AlbumRepository $albumRepository
     * @return self Provides Fluent Interface
     */
    public function setAlbumRepository($albumRepository)
    {
        $this->albumRepository = $albumRepository;
        return $this;
    }

    /**
     * @return ImageRepository
     */
    public function getImageRepository()
    {
        return $this->imageRepository;
    }

    /**
     * @param ImageRepository $imageRepository
     * @return self Provides Fluent Interface
     */
    public function setImageRepository($imageRepository)
    {
        $this->imageRepository = $imageRepository;
        return $this;
    }

}